<?php

namespace PushNotification\Si\Component\Application\Event;

/**
 * Application event reference class.
 */
final class ApplicationEvents
{
    /**
     * event fired when a application is created.
     */
    const PUSHNOT_APPLICATION_CREATED = 'push_not.application.created';

    /**
     * event fired when a application is updated.
     */
    const PUSHNOT_APPLICATION_EDITED = 'push_not.application.edited';

    /**
     * event fired when a application is deleted.
     */
    const PUSHNOT_APPLICATION_DELETED = 'push_not.application.deleted';

    /**
     * event fired when a user logout from an application.
     */
    const PUSHNOT_APPLICATION_LOGOUT = 'push_not.application.logout';
}
