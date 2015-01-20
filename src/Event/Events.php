<?php

namespace Psecio\Parse\Event;

/**
 * List of events fired during a scan
 */
interface Events
{
    /**
     * A scan.start event is fired when the scan starts
     */
    const SCAN_START = 'scan.start';

    /**
     * A scan.complete event is fired when the entire scan as done
     */
    const SCAN_COMPLETE = 'scan.complete';

    /**
     * A scan.file_open event is fired when a new file is opened for scan.
     *
     * The event listener receives an \Psecio\Parse\Event\FileEvent instance.
     */
    const FILE_OPEN = 'scan.file_open';

    /**
     * A scan.file_close event is fired when the scanning of a file is complete
     */
    const FILE_CLOSE = 'scan.file_close';

    /**
     * A scan.file_issue event is fired when a possible security issue is found
     *
     * The event listener receives an \Psecio\Parse\Event\IssueEvent instance.
     */
    const FILE_ISSUE = 'scan.file_issue';

    /**
     * A scan.file_error event is fired when a file error is encountered
     *
     * The event listener receives an \Psecio\Parse\Event\ErrorEvent instance.
     */
    const FILE_ERROR = 'scan.file_error';

    /**
     * A scan.debug event is fired in less important situations
     *
     * The event listener receives an \Psecio\Parse\Event\MessageEvent instance.
     */
    const DEBUG = 'scan.debug';
}
