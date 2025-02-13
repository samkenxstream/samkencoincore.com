<?php

/*
 * This endpoint receives payloads from the `/props` slash command in `#props`.
 *
 * To manage, browse to https://wordpress.slack.com/apps/manage/custom-integrations > Slash Commands > `/props`.
 */

namespace {
	require dirname( __DIR__, 2 ) . '/includes/hyperdb/bb-10-hyper-db.php';
	require dirname( __DIR__, 2 ) . '/includes/slack-config.php';
}

namespace Dotorg\Slack\Props {
	require dirname( __DIR__, 2 ) . '/includes/slack/props/lib.php';

	function get_avatar( $username, $slack_id, $team_id ) {
		global $wpdb;

		$wp_user_id = $wpdb->get_var( $wpdb->prepare(
			"SELECT user_id FROM slack_users WHERE slack_id = %s",
			$slack_id
		) );

		$email = $wpdb->get_var( $wpdb->prepare(
			"SELECT user_email FROM $wpdb->users WHERE ID = %d",
			$wp_user_id
		) );

		$hash = md5( strtolower( trim( $email ) ) );
		return sprintf( 'https://secure.gravatar.com/avatar/%s?s=96d=mm&r=G&%s', $hash, time() );
	}

	if ( constant( __NAMESPACE__ . '\\WEBHOOK_TOKEN' ) === $_POST['token'] ) {
		echo run( $_POST );
	}
}
