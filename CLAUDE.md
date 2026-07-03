# wp-plugin-starter — Laravel-flavored starter за WordPress плъгини

PHP 8.3+ (`wp-plugin-core`, `illuminate/collections`, Carbon) + Vite/TypeScript/Vue 3 SPA за admin страницата. Комуникация с потребителя: български. Код, commit-и и PR-и: английски.

Работният флоу (issue-та, PR-и) идва от плъгина `gws@claude-flow` — `/gws:issue <N>`. Този файл носи само спецификите на проекта.

## Branch-ове
- Базов branch: `main`. Issue branch-ове: `fix|feat|chore/N-kratko-ime` от него, PR към него, squash merge.
- Issue-то се затваря с `Fixes #N` в тялото на commit-а (базовият branch е default — затваря се при merge на PR-а).

## Deploy
- Няма — проектът не се качва на сървър. `/gws:ship` не е приложим тук; доставката е merge в базовия branch.

## Build и commit-и
- Frontend build: `npm run build` (Vite); typecheck: `npm run typecheck` (vue-tsc).
- Тестове: PHPUnit (`vendor/bin/phpunit`); има pre-commit hook от `givanov95/laravel-git-hooks`.
- Зависимости чрез path repositories: `../wp-plugin-core` (Composer symlink) и `../wp-plugin-core-frontend` (npm file:) — проектът е част от трио repo-та.
- Commit стил: Conventional Commits на английски (`fix(scope): ...`).

## GitHub
- Нови issue-та се добавят в project board „gws".
