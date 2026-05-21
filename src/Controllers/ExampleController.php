<?php

namespace WpPluginStarter\Controllers;

use Throwable;
use WP_REST_Request;
use WpPluginCore\Controllers\Controller;
use WpPluginCore\Enums\ValidationRule;
use WpPluginStarter\Http\Request;
use WpPluginStarter\Models\Example;

class ExampleController extends Controller
{
    public function __construct(
        private readonly Example $examples = new Example(),
    ) {
    }

    public function index(WP_REST_Request $wp): void
    {
        $request = Request::fromRest($wp);

        $page    = max(1, (int) $request->input('page', 1));
        $perPage = min(100, max(1, (int) $request->input('per_page', 20)));
        $status  = $request->input('status');

        $result = $this->examples->paginate($page, $perPage, $status);

        $this->success([
            'items'    => $result['items'],
            'total'    => $result['total'],
            'page'     => $page,
            'per_page' => $perPage,
        ]);
    }

    public function store(WP_REST_Request $wp): void
    {
        try {
            $data = Request::fromRest($wp)->validated([
                'title'  => ['required' => true,  'rule' => ValidationRule::STRING],
                'email'  => ['required' => true,  'rule' => ValidationRule::EMAIL],
                'status' => ['required' => false, 'rule' => ValidationRule::STRING],
            ]);
        } catch (Throwable $e) {
            $this->error($e->getMessage(), 'validation_failed', 422);
            return;
        }

        $data['title'] = sanitize_text_field($data['title']);
        $data['email'] = sanitize_email($data['email']);
        if (isset($data['status'])) {
            $data['status'] = sanitize_text_field($data['status']);
        }

        try {
            $id = $this->examples->create($data);
        } catch (Throwable $e) {
            $this->error($e->getMessage(), 'db_error', 500);
            return;
        }

        $this->success($this->examples->find($id), 201);
    }

    public function destroy(WP_REST_Request $wp): void
    {
        $id = (int) $wp->get_param('id');

        if ($id <= 0) {
            $this->error('Invalid id.', 'invalid_id', 422);
            return;
        }

        $deleted = $this->examples->delete($id);

        if ($deleted === 0) {
            $this->error('Not found.', 'not_found', 404);
            return;
        }

        $this->success(['deleted' => $id]);
    }
}
