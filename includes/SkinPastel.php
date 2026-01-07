<?php
/**
 * SkinPastel - Modern implementation using SkinMustache
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * @file
 * @ingroup Skins
 */

namespace MediaWiki\Skins\Pastel;

use MediaWiki\Skins\Pastel\Components\PastelComponentTableOfContents;
use MediaWiki\Skins\Pastel\Components\PastelComponentMainMenu;
use MediaWiki\Skins\Pastel\Components\PastelComponentPageTools;
use MediaWiki\Skins\Pastel\Services\PastelServices;

/**
 * Skin class for Pastel
 *
 * Modern implementation using Mustache templates and component-based architecture.
 */
class SkinPastel extends \SkinMustache {

	/**
	 * @inheritDoc
	 */
	public function getTemplateData(): array {
		$parentData = parent::getTemplateData();

		try {
			$featureManager = PastelServices::getFeatureManager();
			$featureClasses = $featureManager->getFeatureBodyClasses();

			// Filter user menu to only show login/logout
			$this->filterUserMenu( $parentData );

			// Filter page views to remove "Read" button
			$this->filterPageViews( $parentData );

			// Build component data
			$componentData = $this->buildComponentData( $parentData, $featureManager );

			// Merge with parent data and add Pastel-specific enhancements
			return array_merge( $parentData, $componentData, [
				'html-body-classes' => $this->getBodyClasses( $featureClasses ),
			] );
		} catch ( \Exception $e ) {
			error_log( 'Pastel skin error: ' . $e->getMessage() . "\n" . $e->getTraceAsString() );
			throw $e;
		}
	}

	/**
	 * Filter user menu to only show login/logout or username with dropdown
	 */
	private function filterUserMenu( array &$parentData ): void {
		if ( !isset( $parentData['data-portlets']['data-user-menu']['array-items'] ) ) {
			return;
		}

		$items = $parentData['data-portlets']['data-user-menu']['array-items'];
		$filtered = [];
		$user = $this->getUser();

		if ( $user->isRegistered() ) {
			// Logged in: Create dropdown menu
			$username = $user->getName();
			$initial = mb_substr( $username, 0, 1 );

			// Collect dropdown items
			$dropdownItems = [];
			foreach ( $items as $item ) {
				$id = $item['id'] ?? '';
				// Include userpage, preferences, logout
				if ( in_array( $id, [ 'pt-userpage', 'pt-preferences', 'pt-logout' ] ) ) {
					$dropdownItems[] = $item;
				}
			}

			// Create dropdown HTML
			$dropdownHtml = '<div class="pastel-user-dropdown">';
			$dropdownHtml .= '<button class="pastel-user-button" title="' . htmlspecialchars( $username ) . '">' . htmlspecialchars( $initial ) . '</button>';
			$dropdownHtml .= '<div class="pastel-user-dropdown-menu">';
			$dropdownHtml .= '<div class="pastel-user-dropdown-header">' . htmlspecialchars( $username ) . '</div>';
			foreach ( $dropdownItems as $item ) {
				// Extract href and text from HTML
				$html = $item['html'] ?? '';
				$href = '#';
				$text = '';

				// Try to extract href from HTML using regex
				if ( preg_match( '/href="([^"]+)"/', $html, $matches ) ) {
					$href = $matches[1];
				}

				// Extract text from HTML
				$text = strip_tags( $html );

				$dropdownHtml .= '<a href="' . htmlspecialchars( $href ) . '" class="pastel-user-dropdown-item">' . htmlspecialchars( $text ) . '</a>';
			}
			$dropdownHtml .= '</div>';
			$dropdownHtml .= '</div>';

			$filtered[] = [ 'html' => $dropdownHtml, 'id' => 'pt-user-dropdown' ];
		} else {
			// Not logged in: Show login
			foreach ( $items as $item ) {
				$id = $item['id'] ?? '';
				if ( $id === 'pt-login' ) {
					$filtered[] = $item;
				}
			}
		}

		$parentData['data-portlets']['data-user-menu']['array-items'] = $filtered;
	}

	/**
	 * Filter page views to remove "Read" button
	 */
	private function filterPageViews( array &$parentData ): void {
		if ( !isset( $parentData['data-portlets']['data-views']['array-items'] ) ) {
			return;
		}

		$items = $parentData['data-portlets']['data-views']['array-items'];
		$filtered = [];

		foreach ( $items as $item ) {
			$id = $item['id'] ?? '';
			// Skip the "Read" view (ca-view or nstab-*)
			if ( $id === 'ca-view' || strpos( $id, 'ca-nstab-' ) === 0 ) {
				continue;
			}
			$filtered[] = $item;
		}

		$parentData['data-portlets']['data-views']['array-items'] = $filtered;

		// Also rebuild html-items
		$html = '';
		foreach ( $filtered as $item ) {
			$html .= $item['html-item'] ?? '';
		}
		$parentData['data-portlets']['data-views']['html-items'] = $html;
	}

	/**
	 * Build component data for Mustache templates
	 */
	private function buildComponentData( array $parentData, $featureManager ): array {
		$config = $this->getConfig();
		$localizer = $this->getContext();

		// Table of Contents component
		$tocData = [];
		$tocParentData = $parentData['data-toc'] ?? [];
		if ( $featureManager->shouldShowToc( $tocParentData ) ) {
			$tocComponent = new PastelComponentTableOfContents(
				$tocParentData,
				$localizer,
				$config,
				$featureManager
			);
			$tocData = $tocComponent->getTemplateData();
		}

		// Main Menu component (sidebar navigation)
		$menuData = $this->buildMenuData( $parentData );
		$mainMenuComponent = new PastelComponentMainMenu(
			$localizer,
			$featureManager,
			$menuData
		);

		// Page Tools component (edit, history, etc.)
		$toolsData = $this->buildPageToolsData( $parentData );
		$pageToolsComponent = new PastelComponentPageTools(
			$localizer,
			$featureManager,
			$toolsData
		);

		return [
			'data-toc' => $tocData,
			'data-main-menu' => $mainMenuComponent->getTemplateData(),
			'data-page-tools' => $pageToolsComponent->getTemplateData(),
		];
	}

	/**
	 * Build menu data from sidebar
	 */
	private function buildMenuData( array $parentData ): array {
		// Build sidebar using MediaWiki's buildSidebar() method
		// This reads from MediaWiki:Sidebar page
		$sidebar = $this->buildSidebar();
		$menuData = [];

		// Only show "최근 변경사항" section
		$allowedSections = [ '최근 변경사항', 'recent-changes' ];

		// Convert sidebar data to portlet format
		foreach ( $sidebar as $key => $items ) {
			// Skip sections that are not in the allowed list
			if ( !in_array( $key, $allowedSections ) ) {
				continue;
			}

			$portletId = 'p-' . strtolower( str_replace( ' ', '-', $key ) );

			// Set label explicitly for recent changes
			if ( $key === '최근 변경사항' || $key === 'recent-changes' ) {
				$label = $this->msg( 'recent-changes' )->text();
			} else {
				$label = $key;
			}

			$menuData[] = [
				'id' => $portletId,
				'label' => $label,
				'class' => 'mw-portlet mw-portlet-' . $portletId,
				'array-items' => array_map( function ( $item ) {
					return [
						'html' => $item['text'] ?? '',
						'href' => $item['href'] ?? '#',
						'id' => $item['id'] ?? '',
						'class' => $item['class'] ?? '',
					];
				}, $items ),
			];
		}

		return $menuData;
	}

	/**
	 * Build page tools data
	 */
	private function buildPageToolsData( array $parentData ): array {
		$tools = [];

		// Views (read, edit, history, etc.)
		if ( isset( $parentData['data-views'] ) ) {
			$tools = array_merge( $tools, $parentData['data-views']['array-items'] ?? [] );
		}

		// Actions (delete, move, protect, etc.)
		if ( isset( $parentData['data-actions'] ) ) {
			$tools = array_merge( $tools, $parentData['data-actions']['array-items'] ?? [] );
		}

		return $tools;
	}

	/**
	 * Get body classes including feature classes
	 */
	private function getBodyClasses( array $featureClasses ): string {
		$classes = [ 'skin-pastel' ];

		// Add user status class
		if ( $this->getUser()->isRegistered() ) {
			$classes[] = 'user-logged-in';
		} else {
			$classes[] = 'user-anonymous';
		}

		// Add feature classes
		$classes = array_merge( $classes, $featureClasses );

		// Add parent body classes
		$parentClasses = parent::getPageClasses( $this->getTitle() );
		if ( $parentClasses ) {
			$classes[] = $parentClasses;
		}

		return implode( ' ', array_unique( $classes ) );
	}
}
