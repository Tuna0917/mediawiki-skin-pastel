<?php

namespace MediaWiki\Skins\Pastel\Hooks;

use Exception;
use MediaWiki\MediaWikiServices;
use MediaWiki\Title\Title;

class SidebarHooks {

	/**
	 * Handler for SkinBuildSidebar hook
	 * Adds recent changes to the sidebar
	 *
	 * @param \Skin $skin
	 * @param array &$sidebar
	 */
	public static function onSkinBuildSidebar( $skin, &$sidebar ) {
		$dbr = MediaWikiServices::getInstance()->getDBLoadBalancer()->getConnection( DB_REPLICA );
		$recentChanges = [];

		try {
			$actorStore = MediaWikiServices::getInstance()->getActorStore();
			$lang = MediaWikiServices::getInstance()->getContentLanguage();

			$res = $dbr->select(
				'recentchanges',
				[ 'rc_title', 'rc_namespace', 'rc_timestamp', 'rc_actor' ],
				[
					'rc_namespace' => [ NS_MAIN, NS_USER, NS_PROJECT, NS_HELP, NS_TEMPLATE ],
					'rc_bot' => 0,
				],
				__METHOD__,
				[
					'ORDER BY' => 'rc_timestamp DESC',
					'LIMIT' => 5
				]
			);

			foreach ( $res as $row ) {
				$title = Title::makeTitle( $row->rc_namespace, $row->rc_title );
				if ( $title ) {
					$actor = $actorStore->getActorById( $row->rc_actor, $dbr );
					$userName = $actor ? $actor->getName() : '알 수 없음';

					// IP 주소인 경우 앞 두 옥텟만 표시
					if ( filter_var( $userName, FILTER_VALIDATE_IP ) ) {
						$parts = explode( '.', $userName );
						if ( count( $parts ) === 4 ) {
							$userName = $parts[0] . '.' . $parts[1];
						}
					}

					// 시간 표시 (24시간 이내는 상대시간, 이후는 절대시간)
					$timestamp = wfTimestamp( TS_UNIX, $row->rc_timestamp );
					$timeDiff = time() - $timestamp;

					if ( $timeDiff < 60 ) {
						$timeAgo = '방금 전';
					} elseif ( $timeDiff < 3600 ) {
						$timeAgo = floor( $timeDiff / 60 ) . '분 전';
					} elseif ( $timeDiff < 86400 ) {
						$timeAgo = floor( $timeDiff / 3600 ) . '시간 전';
					} else {
						// 24시간 이후는 절대 시간
						$timeAgo = $lang->date( $row->rc_timestamp, true ) . ' ' . $lang->time( $row->rc_timestamp, true );
					}

					$recentChanges[] = [
						'text' => $title->getPrefixedText() . "\n" . $userName . ' · ' . $timeAgo,
						'href' => $title->getLocalURL(),
						'id' => 'rc-' . $title->getArticleID(),
						'active' => false,
					];
				}
			}
		} catch ( Exception $e ) {
			// Log error but don't break the sidebar
			wfDebugLog( 'pastel', 'Error fetching recent changes: ' . $e->getMessage() );
		}

		// Add recent changes section
		if ( empty( $recentChanges ) ) {
			$recentChanges = [
				[
					'text' => '아직 변경사항이 없습니다',
					'href' => Title::newFromText( 'Special:RecentChanges' )->getLocalURL(),
					'id' => 'rc-empty',
					'active' => false,
				]
			];
		}

		$sidebar['recent-changes'] = $recentChanges;
	}
}
