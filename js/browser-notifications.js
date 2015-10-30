( function() {
    /*global RealtimeUpdate: false, BrowserNotifications: false*/
    "use strict";

    if ( !( "Notification" in window ) || typeof( RealtimeUpdate ) === "undefined" || BrowserNotifications.enabled === false ) {
        return;
    }

    var gs_makeNoticeItem = RealtimeUpdate.makeNoticeItem, // Monkey-patch
        showNotification,
        isMention;

    // Ask for notification permissions on page load
    Notification.requestPermission();

    showNotification = function( node ) {
        /* Request permission again in case previous ones were missed
           If request was previouly granted, it doesn't ask again; it just shows
           If request was previouly denied, nothing happens */
        Notification.requestPermission( function( permission ) {
            if ( permission === "granted" ) {
                var $node = $( node ),
                    noticeText = $.trim( $node.find( ".e-content" ).text() ),
                    author = $.trim( $node.find( ".notice-headers .p-author" ).text() );

                new Notification( "New notice from " + author, { body: noticeText } );
            }
        } );
    };

    isMention = function( /* node */ ) {
        /* TODO */

        return true;
    };

    RealtimeUpdate.makeNoticeItem = function( data, callback ) {
        /* Monkey-patch */
        var gs_callback = callback;

        // Call the original "makeNoticeItem", but with our own callback
        gs_makeNoticeItem( data, function( node ) {
            /* Call the original callback */
            gs_callback( node );

            if ( BrowserNotifications.mentions_only === true ) {
                if ( isMention( node ) === true ) {
                    showNotification( node );
                }
            } else {
                showNotification( node );
            }
        } );
    };
}() );
