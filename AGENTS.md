# Awyiss Project – Unified Guide for AI Agents

This is a website project based on Awyiss CMS, a CakePHP 5-based content management system. The CMS core provides the platform; all
website-specific development belongs in the Customer directory created from `customer_skeleton`.

Read this file before changing or creating code. Treat the Customer directory as the application and the Awyiss core as an external
dependency that may be replaced during updates.

## 1. Non-negotiable rules

1. Never modify Awyiss core files, core configuration, core assets or core migrations. Depending on the installation, core files may be in
   `/awyiss/` or `vendor/awyiss/awyiss/awyiss/`.
2. Work in the Customer directory identified by `CUSTOM_DIR` in `.env`. It is usually a copy of `customer_skeleton`.
3. Keep the Customer folder hierarchy aligned with the core when overriding or extending it. Do not invent a parallel application structure.
4. Use the real Customer namespace for new PHP classes. It is the PascalCase form of the customer name, for example `MyProject\Controller`.
5. Make the smallest local change that solves the request. Do not refactor unrelated code or add dependencies, frameworks or build systems
   without a concrete requirement.
6. Do not add tests, documentation files or additional files unless they are explicitly requested or required for the implementation.
7. Follow existing project patterns and CakePHP 5 conventions: plural Table classes, singular Entity classes and the established directory
   structure.
8. Keep Backend and Frontend realms separate. Do not mix their controllers, templates, assets or translations.
9. Reuse Awyiss functionality for media, image conversion, queues and SEO instead of recreating it.
10. Keep credentials and other secrets out of the repository. Use environment-specific configuration for secrets and environment-dependent
    values.

## 2. Mandatory decision process

Before creating custom code, investigate in this order:

1. Existing Awyiss feature
2. Existing Customer implementation
3. Existing Twig template or element
4. Existing Content Template
5. Existing Global Content
6. Existing Widget
7. Existing Awyiss extension or override point
8. New application-specific implementation only when none of the above is suitable

When changing an existing component, inspect its surrounding templates, configuration and related PHP classes first. If core behavior must
change, prefer a Customer override, extension, event listener, Widget or project configuration over editing the core. Choose the least
invasive extension mechanism.

## 3. Awyiss concepts

| Term | Meaning |
| --- | --- |
| Customer / `<customer>` | Project-specific directory selected by `CUSTOM_DIR` in `.env`. It contains all custom code, templates, assets and configuration. |
| Environment | `CONFIG_ENV` in `.env`, such as `development` or `production`. It controls asset compilation, caching and error display. |
| Backend | Administrative realm, normally available below `/backend`. |
| Frontend | Public website realm. |
| Scope | Functional area such as Pages, Media, Forms, a Page Role or a Datatable. |
| Page Role | A page type. `Page` is the default; roles such as News, Event or Product can create separate Backend areas and templates. |
| Page | An object with a unique Frontend URL, except media files. Pages are stored in the `pages` table. |
| Page Template | Defines a page's HTML structure and the Content Areas available to it. |
| Content | An editable building block assigned to a Page and Content Area. |
| Content Template | Defines the editable fields shown in the Backend and the Twig rendering used in the Frontend. |
| Content Area | A named section in a Page Template that can contain Contents. |
| Global Content | A reusable content object that is not assigned to a specific Page. |
| Widget | A self-contained, reusable feature package, normally insertable through the Backend. |
| Datatable | Backend data management for entities that are not Pages. |
| Column System | Awyiss' editorial layout system for Contents, Global Contents and Form Elements; it is not automatically Bootstrap's grid. |

## 4. Project layout

After `composer create-project awyiss/project …`, the relevant structure is typically:

```
project-root/
├── bin/cake                         # CakePHP console; bin/cake.bat is available on Windows
├── webroot/                         # Document root for the web server
├── <customer>/                      # All website-specific work belongs here
│   ├── assets/                      # scss/, css/, js/, img/ …
│   ├── config/                      # awyiss.php, environment overrides and routes.php
│   ├── Controller/                  # Backend/ and Frontend/
│   ├── Event/                       # Backend/ and Frontend/ listeners
│   ├── Form/                        # FormOptions and Protection classes
│   ├── Model/                       # Entity/, Table/, Behavior/, Enum/
│   ├── Survey/                      # SurveyResults classes
│   ├── templates/                   # Backend/ and Frontend/ Twig templates
│   ├── locales/                     # Backend/ and Frontend/ translations, usually .po
│   ├── Twig/Extension/              # Custom Twig extensions
│   ├── View/Cell/                   # Custom View Cells
│   └── Widget/                      # Custom Widgets
├── vendor/awyiss/awyiss/awyiss/     # Awyiss core; never edit
├── .env                             # CUSTOM_DIR, CONFIG_ENV, SECURITY_SALT, …
└── tmp/, logs/
```

Point the web server at `webroot/`, not at the project root. Use the actual Customer path and namespace present in the project rather than
assuming the placeholder `<customer>` literally exists.

## 5. Pages, Contents and Twig

A normal Frontend page follows this model:

`Page → Page Template → Content Areas → Contents → Content Templates`

### Page Templates

Page Template files are in `<customer>/templates/Frontend/page/`. The filename corresponds to the Page Template's configured filename. The
template owns page-level HTML structure and normally renders Content Areas with the `content()` Twig function:

```twig
{% set hero = content('HeroArea') %}
{% if hero %}
	<section class="hero">
		{{ hero|raw }}
	</section>
{% endif %}

<main>
	{{ content('ContentArea') }}
</main>
```

Do not put editable page-specific content directly into a Page Template. Create and configure a suitable Content Template instead.

### Content Templates

Frontend Content Templates are in `<customer>/templates/Frontend/content/`. The filename corresponds to the Content Template configured in
Awyiss. Each template should render one logical component, such as a hero, text/image combination, card, button, accordion, feature list,
image gallery or call to action.

Do not invent Twig fields. Every field used by a Content Template must be provided by its Backend configuration. When adding a Content
Template, also configure its Content Elements and assign it to the appropriate Page Template and Content Area in the Backend.

### Global Contents and Elements

Use Global Contents for information shared across pages, such as header or footer information, contact details, opening hours, reusable
calls to action and promotions. Their Frontend templates are in `<customer>/templates/Frontend/global_content/`. Do not duplicate content
that is conceptually global across multiple Pages.

Reusable Frontend fragments belong in `<customer>/templates/Frontend/element/`. The Customer skeleton commonly includes `header.twig`,
`footer.twig` and `_scripts.twig`. Use elements rather than duplicating markup across Page Templates.

### Twig inheritance

Customer templates mirror the core template structure. Core templates are in `vendor/awyiss/awyiss/awyiss/templates/`; Customer templates
are in `<customer>/templates/`. Prefer `{% extends '@Frontend/…' %}` or `{% extends '@Backend/…' %}` and override only the required blocks.
Do not copy large core templates unless replacing the complete template is necessary.

Relevant paths include:

- `<customer>/templates/Frontend/page/`
- `<customer>/templates/Frontend/content/`
- `<customer>/templates/Frontend/global_content/`
- `<customer>/templates/Frontend/layouts/`
- `<customer>/templates/Frontend/element/`
- `<customer>/templates/Frontend/cell/`
- `<customer>/templates/Backend/`

## 6. Navigation and editorial layout

Navigation is managed through Awyiss Menus. Menus have unique identifiers and should be rendered through the existing Awyiss menu
functionality. Do not hard-code primary navigation into every Page Template. Identifiers such as `main`, `footer`, `meta` and `sidebar` are
only examples; use the identifiers actually configured for this project.

Awyiss' Column System controls the editorial layout of Contents, Global Contents and Form Elements.
Do not introduce Bootstrap column classes merely because a layout has columns.
Use the configured Column System unless the project explicitly uses another system; CSS controls the visual appearance.

## 7. Assets and responsive Frontend code

Website assets belong in `<customer>/assets/`:

- SCSS and CSS: `<customer>/assets/scss/` and `<customer>/assets/css/`
- JavaScript: `<customer>/assets/js/`
- Images: `<customer>/assets/img/`

The main Frontend stylesheet is `<customer>/assets/scss/full.scss`. Awyiss compiles SCSS automatically outside production environments. Its
SCSS compiler does not support `@use`; use `@import` where needed. If SCSS is intentionally not used, standalone CSS may be placed in
`assets/css/`. Do not introduce a separate Frontend build system unless required by the project.

Backend preview helper styles may include `audit_history.scss` / `.css`, `jodit.scss` / `.css` and `tinymce.scss` / `.css`.

Load assets through Awyiss' `AssetHelper` and existing asset-loading mechanisms instead of hard-coding paths. Awyiss does not automatically
bundle or process Frontend JavaScript; prefer small, focused vanilla JavaScript. Do not introduce React, Vue, Node-based tooling or another
JavaScript framework unless explicitly required. Implement responsive behavior with CSS rather than JavaScript viewport detection, and avoid
desktop-only dimensions unless a design requirement demands them.

## 8. PHP extension points

Use CakePHP conventions and place custom classes in the Customer directory. Available extension points include:

- Controllers
- Entities and Tables
- Behaviors and Enums
- Events
- Widgets
- custom View Cells
- custom Twig extensions

### Core overrides

To override a core class, create the same relative path under `<customer>/` so Awyiss can prefer the Customer version. For example:

```
<customer>/Controller/Backend/UsergroupsController.php
namespace MyProject\Controller\Backend;
```

Replace `MyProject` with the actual Customer namespace. Extend existing classes where possible instead of copying or replacing them.

### Controllers and routes

Do not create a Frontend Controller just because a new webpage is needed. Normal content pages are handled by Pages and Page Templates.
Create a custom Frontend route or Controller only for functionality that genuinely needs request handling, such as a custom application
feature or special endpoint. Custom Frontend routes belong in `<customer>/config/routes.php`.

Backend routing is normally automatic below `/backend/<locale>/<controller>/…`.
For a new Backend area, add the required Customer Controller, Model and templates.
The Backend Datatable scope can manage entities other than Pages.

### Events, Forms, Surveys and Widgets

- Event listeners: `<customer>/Event/Backend/` or `<customer>/Event/Frontend/`, conventionally named `SomethingListener.php`.
- Form options: `<customer>/Form/YourFormNameFormOptions.php`.
- Custom form protection: `<customer>/Form/Protection/`.
- Survey result logic: `<customer>/Survey/YourSurveyNameSurveyResults.php`.
- Widgets: `<customer>/Widget/`, following the existing Widget interfaces or base classes.

Use a Widget for dynamic or reusable PHP-backed functionality that should remain insertable through the Backend, such as dynamic listings,
API-backed content, calculations, custom interactive components or context-dependent content. Do not create a Widget when a normal Content
Template is sufficient.

## 9. Configuration, database and localization

- Static project configuration: `<customer>/config/awyiss.php`.
- Environment overrides: `<customer>/config/<environment>/awyiss.php`, where `<environment>` comes from `CONFIG_ENV`.
- Custom routes: `<customer>/config/routes.php`.
- Scope-specific options: often under `<customer>/Configuration/ConfigOptions/`.

Database changes belong in the Customer configuration or migration structure. Never modify core migrations. Use CakePHP Tables and Entities;
do not introduce another ORM or database abstraction layer.

Awyiss separates Backend and Frontend languages. Put Frontend translations in the Customer localization structure and do not hard-code
user-visible strings that are intended to be translated. Use Awyiss' existing localization mechanisms rather than creating a separate
translation system.

## 10. Attributes

Attributes (Custom Fields) are **table-specific extensions** of the data model.  
They allow adding extra fields to existing scopes (Pages, Contents, Media, Users, Usergroups, Page Roles, Datatables, …) **without**
modifying core code or the core database tables.

### How it works

1. In the Backend under **System → Attributes** an Attribute is created for a specific **Scope**.
2. Awyiss automatically creates (or extends) a dedicated table:
    - Naming scheme: `attributes_<scope>`  
      Examples: `attributes_pages`, `attributes_contents`, `attributes_news`, `attributes_media` …
3. Each Attribute becomes its own **column** in that table.
4. The values are bound 1:1 to the entity of the scope via a `hasOne` association (`propertyName = 'attributes'`).

### What happens when creating / updating an Attribute

When an Attribute is saved, Awyiss uses CakePHP Bake to generate or update:

- A Migration (Create / Add / Alter / Remove / Drop)
- Table class: `<customer>/Model/Table/Attributes<Scope>Table.php`  
  (e.g. `AttributesPagesTable.php`)
- Entity class: `<customer>/Model/Entity/Attributes<Scope>.php`  
  (e.g. `AttributesPage.php`)

The column uses the Attribute’s **identifier** as the column name and entity property.

### Accessing Attributes in code

**Entity (PHP)**

```php
// Direct property access (camelCase)
$page->largeHeroSection

// or via the association
$page->attributes->largeHeroSection
```

**Twig**

```twig
{{ page.largeHeroSection }}
{{ page.attributes.largeHeroSection }}
```

**Table methods (via AttributesBehavior)**

```php
$table->hasAttributes()
$table->getAttributes()                 // all defined Attributes for the scope
$table->getAttributesTable()            // the Attributes*Table instance
$table->getAttributesTableName()        // 'attributes_pages'
$table->getAttributesTableName(true)    // 'AttributesPages'
```

### Important rules for AI Agents

- Scopes that support Attributes declare `public const bool ATTRIBUTABLE = true;` in their Table class (this is the default in
  `\Awyiss\Model\Table`).
- Attributes for **Contents** and **Global Contents** must additionally be assigned to a Content Template / Global Content Template,
  otherwise they will not appear in the form.
- The value tables (`attributes_*`) only store the data of individual records – the Attribute definitions themselves live in the central
  `attributes` table.
- Never manually alter `attributes_*` tables or the generated Model classes when the change can be done through the Backend. Always let the
  Attributes Backend handle schema changes so that migrations and model classes stay in sync.

### Typical use cases

- Extra checkboxes / select fields on Pages (e.g. “Large Hero Section”)
- Additional fields on Contents (background colour, layout options, …)
- Extending Datatables and custom Page Roles
- Translatable and non-translatable fields depending on the scope

## 11. Backend-first CMS objects

Many Awyiss objects are database-driven and must be created and configured in the Backend before they can be used in code. Creating only a
Twig file, PHP class or database reference does not register the object with Awyiss and may result in missing fields, templates or data.

The required order is:

1. Create the object in the appropriate Backend scope.
2. Configure its identifier, fields, assignments and other options in the Backend.
3. Create the matching Customer-side Twig or PHP implementation when needed.
4. Use only the configured identifier and fields in code, then verify the result in both Backend and Frontend.

This applies in particular to:

- **Page Templates:** Create and configure the Page Template in the Backend first, then add the matching file in
  `<customer>/templates/Frontend/page/` using its configured filename.
- **Content Templates:** Create the Content Template and its Content Elements in the Backend first. Assign it to the relevant Page Templates
  and Content Areas before using its matching file in `<customer>/templates/Frontend/content/`.
- **Page Roles:** Create and configure the Page Role in the Backend before using its identifier or generated ID in custom code. Awyiss also
  keeps the Page Role enum current in `<customer>/Model/Enum/PageRole.php`.
- **Datatables:** Create and configure the Datatable in the Backend before referring to its identifier, fields or records in custom code.
  Add Customer-side Controllers, Models or templates only where the configured Datatable requires custom behavior.
- **Attributes:** Create the Attribute under **System → Attributes** for the correct Scope before accessing its property in PHP or Twig. For
  Contents and Global Contents, assign it to the relevant Content Template or Global Content Template as well.

### Generated seed files

After an Attribute, Page Role or Datatable is saved in the Backend, Awyiss automatically creates or updates the corresponding seed file in
the Customer directory:

```
<customer>/config/Seeds/AttributesSeed.php
<customer>/config/Seeds/DatatablesSeed.php
<customer>/config/Seeds/PageRolesSeed.php
<customer>/Model/Enum/PageRole.php
```

These generated files contain information required by the application, including Page Role IDs, the current Page Role enum cases,
Attribute names and types, Datatable definitions and other Backend configuration.

For example, the Page Role enum is located at `dummy_customer/Model/Enum/PageRole.php` when the Customer directory is named
`dummy_customer`.

If the Backend is hosted remotely and development is not performed directly on that host, download these files from the remote host after
every relevant Backend change. Place them in the matching Customer path locally and keep them synchronized with the Backend configuration
before using the changed objects in code or deploying the project.

Do not assume that a Page Template, Content Template, Page Role, Datatable or Attribute exists because a corresponding filename, class or
property is present in the Customer directory. Do not hard-code fields or metadata that have not been defined in the Backend or generated
seed files.

## 12. Installation and runtime

Requirements:

- PHP ≥ 8.4, Composer and MySQL 5.7+ or MariaDB 10.3+
- PHP extensions: `curl`, `dom`, `fileinfo`, `gd` or `imagick`, `intl`, `libxml`, `openssl`, `posix`, `zip` and `zlib`
- Web server with URL rewriting and symlink support

Recommended cronjobs:

```
*/10 * * * * cd /path/to/project && bin/cake queue run -q -g general >> logs/cron.log 2>&1
*/1  * * * * cd /path/to/project && bin/cake media convert_files --include-avif --include-webp -q
```

The Backend is normally available at `https://example.com/backend`. To reset a password, use `bin/cake awyiss reset_password`.

## 13. Useful commands

Run these from the project root. On Windows, use `bin\cake.bat` instead of `bin/cake` where necessary.

```bash
bin/cake awyiss install          # runs automatically on create-project
bin/cake awyiss reset_password
bin/cake awyiss backup
bin/cake media detect_available_commands
bin/cake media convert_files --include-avif --include-webp
bin/cake queue run -q -g general
bin/cake scss compile -r Backend   # or Frontend
bin/cake integrity_check check -mi
```

## 14. Documentation and troubleshooting order

When unsure about Awyiss behavior, inspect the Customer implementation first, then the relevant core implementation without modifying it,
and finally consult the official documentation:

- https://docs.awyiss.2f.media/
- https://docs.awyiss.2f.media/understanding-awyiss/core-concepts.html
- https://docs.awyiss.2f.media/understanding-awyiss/folders-and-files-structure.html
- https://docs.awyiss.2f.media/understanding-awyiss/theming-and-customization.html
- https://docs.awyiss.2f.media/getting-started/installation.html

Relevant documentation areas include Core Concepts, Folders and Files Structure, Theming and Customization, Pages, Page Templates,
Contents, Content Templates, Content Areas, Global Contents, Menus, Column Systems, Widgets, Localization, Configuration, Controllers and
Models.

## 15. New project checklist

1. Run `composer create-project awyiss/project my-site`.
2. Complete the installer prompts for customer name, database, administrator and environment.
3. Point the web server at `webroot/` and configure the recommended cronjobs.
4. Log into `/backend`.
5. Configure Languages, Page Templates, Content Templates, Content Elements and Content Areas in the Backend.
6. Create Pages and Contents through the Backend.
7. Customize `<customer>/templates/Frontend/` and `<customer>/assets/`.
8. Add only required custom Controllers, Models, Events, Forms or Widgets under `<customer>/`.
9. Configure SEO, Menus, Forms and Media through the Backend.

When modifying an existing feature, verify the change in the relevant Backend and Frontend realm and preserve the project's existing
conventions.
