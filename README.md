# WP Plugin Starter

Laravel-flavored starter for WordPress plugins:

- **PHP 8.3+** with [`wp-plugin-core`](../wp-plugin-core) — service providers,
  REST + nonce wiring, admin menus, validation, safe `$wpdb` wrapper.
- **Vite + TypeScript + Vue 3** SPA mounted on the plugin's admin page.
- **`Request` wrapper**, **`config()` helper**, **migration runner** for the
  Laravel feel.
- **`illuminate/collections` + Carbon** for fluent data manipulation without a
  Laravel app.

## Requirements

| Dependency | Version |
| --- | --- |
| PHP        | 8.3+    |
| Composer   | 2+      |
| Node.js    | 18+ (20+ recommended) |
| WordPress  | 6.4+    |

## How the three packages fit together

This starter is **one of three** repositories. Each has a distinct role and a
different lifecycle:

| Repo | What it is | When you touch it |
| --- | --- | --- |
| [`wp-plugin-core`](../wp-plugin-core) | Reusable PHP library (service providers, REST helpers, DB, validation). Not a plugin. | Rarely — only to fix bugs or add features shared by *all* plugins. |
| [`wp-plugin-core-frontend`](../wp-plugin-core-frontend) | Reusable TS library (`RestApi`, `NotificationManager`). Not an app. | Rarely — same as above, for shared frontend code. |
| `wp-plugin-starter` *(you are here)* | A concrete plugin skeleton consuming the two libraries. | Every new plugin — clone, rename, write business logic. |

```
   ┌──────────────────────────────┐    ┌──────────────────────────────┐
   │ wp-plugin-core (Composer)    │    │ wp-plugin-core-frontend (npm)│
   │   • PluginServiceProvider    │    │   • RestApi                  │
   │   • RestEndpointService...   │    │   • NotificationManager      │
   │   • AdminMenu, Controller    │    │                              │
   │   • Database, Pagination     │    │                              │
   └──────────────┬───────────────┘    └──────────────┬───────────────┘
                  │ required via                      │ required via
                  │ composer.json                     │ package.json
                  └─────────────┬─────────────────────┘
                                ▼
                ┌──────────────────────────────────┐
                │ wp-plugin-starter                │
                │   ↓ rename.sh                    │
                │ your-plugin (clone of starter)   │
                │   • business logic               │
                │   • routes, models, pages, UI    │
                └──────────────────────────────────┘
```

### Workflow for a new plugin

1. **Leave the libraries alone.** `wp-plugin-core` and
   `wp-plugin-core-frontend` are shared dependencies — treat them as you would
   any vendor package.
2. **Clone the starter** as your new plugin repo (GitHub *Use this template*
   or `gh repo create --template`).
3. **Run `bin/rename.sh`** to rebrand it (slug, namespace, constants, plugin
   file name) — see [Quick start](#quick-start).
4. **Write business logic.** Add controllers, models, migrations, admin pages,
   Vue components.
5. **If you find a bug or missing feature in a library**, fix it in
   `wp-plugin-core` / `wp-plugin-core-frontend` and tag a new version. Every
   plugin that depends on that library gets the fix on its next
   `composer update` / `npm update`.

### Publishing

| | Where it lives | How consumers pull it |
| --- | --- | --- |
| `wp-plugin-core` | Packagist (or private VCS) | `composer require givanov95/wp-plugin-core` |
| `wp-plugin-core-frontend` | npm (public or private registry) | `npm install @givanov95/wp-plugin-core-frontend` |
| `wp-plugin-starter` | Private GitHub repo marked as **template** | `gh repo create --template givanov95/wp-plugin-starter` |

Until you publish the libraries, `composer.json` and `package.json` reference
them as local path repos (`../wp-plugin-core`, `file:../wp-plugin-core-frontend`).
See [Caveats](#caveats).

## Quick start

```bash
# 1. Clone (or use as GitHub template)
gh repo create acme-bookings --private --template givanov95/wp-plugin-starter
cd acme-bookings

# 2. Rename the starter to your plugin
bin/rename.sh acme-bookings AcmeBookings "Acme Bookings"

# 3. Install
composer install
npm install

# 4. Build assets (production)
npm run build

# 5. Symlink into WordPress
ln -s "$PWD" /path/to/wordpress/wp-content/plugins/acme-bookings
```

Activate the plugin from WP Admin → Plugins. The activation hook runs
migrations, creating `{prefix}_acme_bookings_examples`.

> Both `vendor/` and `node_modules/` are git-ignored. On a fresh server you
> must run `composer install` and `npm install && npm run build` before
> activating the plugin.

## Dev workflow

```bash
npm run dev          # starts Vite on :5173, creates .vite-dev flag
# edit, see HMR

npm run build        # production build (removes .vite-dev)
npm run typecheck    # vue-tsc --noEmit
```

When `.vite-dev` exists in the plugin root, the PHP side loads assets from the
Vite dev server. Without it, it reads `dist/.vite/manifest.json`.

## Project layout

```
.
├── plugin.php                       # WP plugin header + bootstrap
├── uninstall.php                    # drops tables on deletion
├── config/
│   └── app.php                      # config('app.*')
├── src/
│   ├── Bootstrap/Plugin.php         # app entry, registers providers
│   ├── Support/Config.php           # dot-notation config repository
│   ├── Support/helpers.php          # config(), plugin_path(), plugin_asset()
│   ├── Http/Request.php             # WP_REST_Request wrapper
│   ├── Database/
│   │   ├── Migration.php            # base migration class
│   │   ├── Migrator.php             # discovers + runs migrations
│   │   └── Migrations/0001_*.php    # one file per migration
│   ├── Providers/
│   │   ├── AssetsServiceProvider.php
│   │   ├── ApiServiceProvider.php
│   │   └── AdminMenuServiceProvider.php
│   ├── Controllers/ExampleController.php
│   ├── Admin/Pages/ExamplePage.php
│   └── Models/Example.php
├── assets/
│   ├── js/
│   │   ├── main.ts                  # mounts Vue app
│   │   ├── App.vue                  # example UI
│   │   ├── env.d.ts                 # Window typings
│   │   └── api/ExampleApi.ts        # typed REST client
│   └── css/main.css
└── languages/                       # .pot / .po / .mo files
```

---

## Working with the toolkit

### Configuration — `config()`

Config files live in `config/` and return arrays. Read with dot notation:

```php
config('app.name');                        // "WP Plugin Starter"
config('app.vite.dev_server_url');         // "http://localhost:5173"
config('app.missing.key', 'fallback');     // "fallback"
```

To add a new config file, drop `config/<name>.php` returning an array. It will
be available as `config('<name>.*')` automatically.

### Controllers + `Request`

The `Request` wrapper sits on top of `WP_REST_Request` and gives you a
Laravel-ish API for reading input and validating it.

```php
use WpPluginStarter\Http\Request;
use WpPluginCore\Controllers\Controller;
use WpPluginCore\Enums\ValidationRule;
use WP_REST_Request;

class WidgetController extends Controller
{
    public function store(WP_REST_Request $wp): void
    {
        $request = Request::fromRest($wp);

        // Read raw input
        $title = $request->input('title');
        $page  = (int) $request->input('page', 1);

        // Validate (throws InvalidArgumentException on failure)
        $data = $request->validated([
            'title' => ['required' => true,  'rule' => ValidationRule::STRING],
            'email' => ['required' => true,  'rule' => ValidationRule::EMAIL],
            'age'   => ['required' => false, 'rule' => ValidationRule::INT],
        ]);

        $this->success($data, 201);
    }
}
```

Available methods on `Request`: `input($key, $default)`, `all()`, `only($keys)`,
`has($key)`, `validated($rules)`, `header($name)`, `ip()`.

### Validation rules

Built-in `ValidationRule` cases (from `wp-plugin-core`):

| Rule     | Behavior                                      |
| -------- | --------------------------------------------- |
| `STRING` | accept as-is                                  |
| `INT`    | must pass `FILTER_VALIDATE_INT`, cast to int  |
| `BOOL`   | parse via `FILTER_VALIDATE_BOOLEAN`           |
| `EMAIL`  | must pass `FILTER_VALIDATE_EMAIL`             |
| `URL`    | must pass `FILTER_VALIDATE_URL`               |
| `RAW`    | accept HTML (use with `wp_kses_post` on save) |

For sanitization, call `$this->sanitize($data, $rules)` from `Controller`.

### Response envelope

`Controller::success()` and `Controller::error()` always return the same shape,
which the frontend `RestApi` knows how to read:

```jsonc
// Success
{ "success": true, "data": { ... } }

// Error
{
  "success": false,
  "error": { "code": "validation_failed", "message": "...", "data": null }
}
```

In a controller:

```php
$this->success(['id' => 7]);                              // 200
$this->success($item, 201);                               // 201
$this->error('Bad input', 'validation_failed', 422);      // 422
$this->error('Not found', 'not_found', 404);              // 404
```

### REST endpoints

1. Add a method on a controller.
2. Register it in `src/Providers/ApiServiceProvider.php`:

```php
$this->addRestEndpoint(
    namespace: $ns,
    route:     '/widgets',
    callback:  [new WidgetController(), 'index'],
    method:    'GET',
    public:    false,             // requires capability check
    capability: 'manage_options', // default 'read' when omitted
);
```

The full route becomes `/wp-json/<namespace>/widgets`. Nonces are auto-issued
on the PHP side, exposed to JS via `window.WpPluginStarter`, and verified by
the core `permission_callback`. You never wire nonces by hand.

### Database & models

The starter ships a thin `Example` model wrapping `WpPluginCore\Database`.
Pattern: one model per table; declare the table name + allowed columns;
expose query methods.

```php
use WpPluginCore\Database\Database;

class Widget
{
    private const TABLE = 'wp_plugin_starter_widgets';
    private const COLUMNS = ['id', 'name', 'status', 'created_at'];

    private function db(): Database
    {
        return new Database(self::TABLE, allowedColumns: self::COLUMNS);
    }

    public function active(): array
    {
        return $this->db()->where(['status' => 'active']);
    }

    public function create(array $data): int
    {
        return $this->db()->insert($data + ['created_at' => current_time('mysql')]);
    }
}
```

`Database` API: `insert()`, `update()`, `delete()`, `find()`, `first()`,
`where()`, `count()`, `paginate()`. All column identifiers are validated
against the `allowedColumns` whitelist.

### Migrations

Create `src/Database/Migrations/000X_<name>.php` returning a class that extends
`Migration`. The version prefix (`000X`) must be numeric and unique.

```php
use WpPluginStarter\Database\Migration;

return new class () extends Migration {
    public function up(): void
    {
        $table   = $this->wpdb()->prefix . 'wp_plugin_starter_widgets';
        $charset = $this->charsetCollate();

        dbDelta("CREATE TABLE {$table} (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            name VARCHAR(191) NOT NULL,
            status VARCHAR(20) NOT NULL DEFAULT 'active',
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) {$charset};");
    }

    public function down(): void
    {
        $table = $this->wpdb()->prefix . 'wp_plugin_starter_widgets';
        $this->wpdb()->query("DROP TABLE IF EXISTS `{$table}`");
    }
};
```

Migrations run on plugin activation. Already-applied versions are skipped
(the highest applied version is stored in the `wp_plugin_starter_db_version`
option). To trigger manually: `(new Migrator())->migrate();`.

### Admin pages

1. Create a `Page` subclass under `src/Admin/Pages/`.
2. Make a provider that implements `ShouldHaveAdminMenu`:

```php
return AdminMenu::submenu(
    parentSlug: config('app.slug'),
    pageTitle:  'Widgets',
    menuTitle:  'Widgets',
    capability: 'manage_options',
    menuSlug:   config('app.slug') . '-widgets',
    pageRenderCallback: fn () => print (new WidgetsPage())->render(),
);
```

3. Add the provider class to `$adminMenuProviders` in
   `src/Bootstrap/Plugin.php`. Top-level menus register before submenus.

### Frontend — REST client

Extend `RestApi` once per resource; subclasses get nonce wiring, debug
logging, and typed errors.

```ts
import { RestApi } from "@givanov95/wp-plugin-core-frontend";

export class WidgetApi extends RestApi {
    list() {
        return this.restFetch<{ success: true; data: Widget[] }>(
            "wp-plugin-starter/v1/widgets",
            "GET",
        );
    }
}

export const widgetApi = new WidgetApi({
    windowPropertyName: "WpPluginStarter",
    options: { logErrors: true, debug: import.meta.env.DEV },
});
```

Errors come as `RestApiError` with `.status`, `.code`, `.message`, `.data`.

### Frontend — Vue + `@/` alias

`@/` resolves to `assets/js/`:

```ts
import App from "@/App.vue";
import { widgetApi } from "@/api/WidgetApi";
```

For a new component, drop a `.vue` file under `assets/js/components/` (or
anywhere under `assets/js/`) and import it the same way.

### Toast notifications

```ts
import { NotificationManager } from "@givanov95/wp-plugin-core-frontend";

const toasts = NotificationManager.getInstance({
    namespace: "wp-plugin-starter",   // isolates styles + container
    position: "top-right",
    duration: 3000,
});

toasts.success("Saved");
toasts.error("Failed to save");
toasts.warning("Email is required");
toasts.info("Loading…");
```

Different `namespace` values produce independent stacks — safe when multiple
plugins built on the toolkit run on the same admin page.

### Collections & dates

`illuminate/collections` and Carbon are required, so use them where they help:

```php
use Illuminate\Support\Collection;
use Carbon\Carbon;

$active = Collection::make($examples)
    ->filter(fn ($row) => $row['status'] === 'active')
    ->sortByDesc('created_at')
    ->take(10)
    ->values()
    ->all();

$since = Carbon::parse($row['created_at'])->diffForHumans(); // "2 hours ago"
```

These work standalone — no Laravel container needed.

---

## Testing endpoints

The example REST endpoints require a logged-in admin (capability
`manage_options`) plus a valid `X-WP-Nonce`. Easiest ways to call them:

- **From the admin page** (where the SPA runs) — `ExampleApi` already wires
  the nonce; just open DevTools to see the calls.
- **From WP-CLI**:

  ```bash
  wp eval 'echo wp_create_nonce("wp_rest");'
  # then:
  curl -H "X-WP-Nonce: <nonce>" \
       -H "Cookie: wordpress_logged_in_...=..." \
       https://site.test/wp-json/wp-plugin-starter/v1/examples
  ```

- **From the browser console** on a logged-in admin page:

  ```js
  await fetch("/wp-json/wp-plugin-starter/v1/examples", {
      headers: { "X-WP-Nonce": wpApiSettings.nonce },
  }).then(r => r.json());
  ```

## Uninstall

Deleting the plugin from WP Admin → Plugins triggers `uninstall.php`, which:

- drops `{prefix}wp_plugin_starter_examples`
- deletes the `wp_plugin_starter_db_version` option

If you add tables or options later, add them to the lists in `uninstall.php`.

## Troubleshooting

| Symptom | Likely cause |
| --- | --- |
| `Vite manifest not found at: .../dist/.vite/manifest.json` | You haven't run `npm run build` yet, or `.vite-dev` is missing while in dev. |
| In dev, browser fails to load `http://localhost:5173/...` | Vite dev server isn't running, or CORS / port blocked. Run `npm run dev` and check the URL in `config/app.php`. |
| `403 rest_invalid_nonce` | Nonce expired (24h) or you're not logged in. Reload the admin page. |
| `404` on `/wp-json/<ns>/...` | Permalinks need re-saving (Settings → Permalinks → Save) or the endpoint isn't registered (check `ApiServiceProvider`). |
| Admin assets don't load on your page | `AssetsServiceProvider::enqueueOnAdmin()` checks the screen id. Update the check when adding new admin pages. |
| `Class WpPluginStarter\... not found` | Run `composer dump-autoload`. |

To enable verbose REST logs, set `WP_DEBUG` and `WP_DEBUG_LOG` in `wp-config.php`,
then watch `wp-content/debug.log`.

---

## Caveats

- `composer.json` references `wp-plugin-core` as a local path repository
  (`../wp-plugin-core`). When you publish the core to Packagist, drop the
  `repositories` block and pin a real version.
- `package.json` references `@givanov95/wp-plugin-core-frontend` as
  `file:../wp-plugin-core-frontend`. Same deal — switch to a versioned npm
  release when published.
- The admin assets are gated by a screen-id check in `AssetsServiceProvider`
  so they only load on this plugin's page. Update the check (or override
  `enqueueOnAdmin()` / `enqueueOnFrontend()`) as your plugin grows.

## License

MIT — see [LICENSE](LICENSE).
