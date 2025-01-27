<?php

declare(strict_types=1);

namespace PhpMyAdmin\Controllers\Table;

use PhpMyAdmin\Controllers\AbstractController;
use PhpMyAdmin\Http\ServerRequest;
use PhpMyAdmin\ResponseRenderer;
use PhpMyAdmin\Template;
use PhpMyAdmin\Url;

use function __;
use function is_array;

final class ExportRowsController extends AbstractController
{
    public function __construct(
        ResponseRenderer $response,
        Template $template,
        private ExportController $exportController,
    ) {
        parent::__construct($response, $template);
    }

    public function __invoke(ServerRequest $request): void
    {
        $GLOBALS['active_page'] ??= null;
        $GLOBALS['single_table'] ??= null;
        $GLOBALS['where_clause'] ??= null;

        if (isset($_POST['goto']) && (! isset($_POST['rows_to_delete']) || ! is_array($_POST['rows_to_delete']))) {
            $this->response->setRequestStatus(false);
            $this->response->addJSON('message', __('No row selected.'));

            return;
        }

        // Needed to allow SQL export
        $GLOBALS['single_table'] = true;

        // As we got the rows to be exported from the
        // 'rows_to_delete' checkbox, we use the index of it as the
        // indicating WHERE clause. Then we build the array which is used
        // for the /table/change script.
        $GLOBALS['where_clause'] = [];
        if (isset($_POST['rows_to_delete']) && is_array($_POST['rows_to_delete'])) {
            foreach ($_POST['rows_to_delete'] as $eachWhereClause) {
                $GLOBALS['where_clause'][] = $eachWhereClause;
            }
        }

        $GLOBALS['active_page'] = Url::getFromRoute('/table/export');

        ($this->exportController)($request);
    }
}
