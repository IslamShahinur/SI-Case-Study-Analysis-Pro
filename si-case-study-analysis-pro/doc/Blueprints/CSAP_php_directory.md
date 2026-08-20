# CSAP PHP Directory — File Roles, Dependencies, Licensing & Data Extraction

Companion to `SI_CSAP_Production_blueprint.md`. This document maps the plugin's directory tree to concrete PHP file responsibilities, the dependency order they must load in, how the licensing SDK is wired in, and where the data-extraction pipeline lives.

---

## 1. Full Directory Tree

```
si-case-study-analysis-pro/
│
├── assets/
│   ├── css/{admin/, frontend/}
│   └── js/{admin/, frontend/}
│
├── includes/
│   ├── SI Framework/
│   │   ├── Core/
│   │   │   ├── Application.php
│   │   │   ├── Bootstrap.php
│   │   │   ├── Container.php
│   │   │   ├── Loader.php
│   │   │   ├── Requirements.php
│   │   │   └── Manifest.php
│   │   ├── Engine/
│   │   │   ├── EngineManager.php
│   │   │   ├── OwnershipManager.php
│   │   │   ├── FeatureManager.php
│   │   │   └── Renderer.php
│   │   └── Premium/
│   │       ├── LicenseController.php
│   │       ├── LicenseClientInterface.php
│   │       ├── LicenseState.php
│   │       └── SDK/
│   │           ├── si-sdk-loader.php
│   │           ├── si-sdk-config.php
│   │           ├── includes/class-si-api-client.php
│   │           ├── includes/class-si-license-client.php
│   │           └── sample-integration.php
│   │
│   ├── Modules/
│   │   ├── Cases/
│   │   ├── Context/
│   │   ├── Timeline/
│   │   ├── Problems/
│   │   ├── Stakeholders/
│   │   ├── Sources/
│   │   ├── Evidence/
│   │   ├── Claims/
│   │   ├── Data/
│   │   ├── Extraction/            (Data Extraction, Persistence & Reuse pipeline)
│   │   ├── Frameworks/
│   │   ├── RootCause/
│   │   ├── Alternatives/
│   │   ├── Decisions/
│   │   ├── Risks/
│   │   ├── Recommendations/
│   │   ├── Implementation/
│   │   ├── Outcomes/
│   │   ├── Findings/
│   │   ├── Lessons/
│   │   └── Publishing/
│   │
│   ├── Admin/
│   │   ├── Dashboard/, Cases/, Sources/, Evidence/, Stakeholders/,
│   │   │   Analysis/, Frameworks/, Decisions/, Risks/, Recommendations/,
│   │   │   Findings/, Publishing/, License/, Settings/, Tools/
│   │
│   └── Gutenberg/
│       ├── Blocks/  (one folder per block — see §6)
│       ├── Patterns/
│       ├── Blueprints/
│       └── Integration/
│
├── templates/
├── languages/
├── vendor/
├── manifest.php
├── readme.txt
├── uninstall.php
└── si-case-study-analysis-pro.php
```

## 2. SI Framework / Core — Bootstrap Chain

These files load first, in this order, and nothing else in the plugin may run before them.

| File | Role | Depends On |
|---|---|---|
| `si-case-study-analysis-pro.php` | Plugin entry file (headers, constants, `require_once` of `manifest.php` and `Core/Bootstrap.php`). Registers activation/deactivation/uninstall hooks. | — |
| `manifest.php` | Returns plugin metadata array (slug, version, min PHP/WP versions, module list) consumed by `Requirements.php` and `Manifest.php`. | — |
| `Core/Requirements.php` | Verifies PHP/WP version and required extensions before anything else boots; halts boot with an admin notice on failure. | `manifest.php` |
| `Core/Container.php` | Minimal dependency-injection container (service binding/resolution) used by every other class instead of manual `new`. | — |
| `Core/Loader.php` | Autoload/registration layer (PSR-4-style) that wires module classes into the `Container`. | `Container.php` |
| `Core/Application.php` | Central application object; owns the `Container`, exposes `boot()`, wires WordPress hooks to internal services. | `Container.php`, `Loader.php` |
| `Core/Bootstrap.php` | Orchestrates the full boot sequence: `Requirements` → `Container` → `Loader` → `Application::boot()`. | All of the above |
| `Core/Manifest.php` | Runtime accessor for `manifest.php` data (version checks, module registry, feature flags). | `manifest.php` |

## 3. SI Framework / Engine — Cross-Cutting Services

| File | Role | Depends On |
|---|---|---|
| `Engine/EngineManager.php` | Registers and coordinates all Modules (§4); single place that knows the full list of active modules. | `Core/Container.php` |
| `Engine/OwnershipManager.php` | **Mandatory gateway for every mutation.** Enforces `Request → Capability → Nonce → Validation → OwnershipManager → Repository → Mutation`. No Repository may be written to except through this class. | `Core/Container.php` |
| `Engine/FeatureManager.php` | The single entitlement gateway. Wraps `LicenseState` (§4) to answer "is feature X available on this site?" Modules and Admin screens call `FeatureManager::isEnabled('...')` — they never touch `LicenseController` or the SDK directly. | `Premium/LicenseState.php` |
| `Engine/Renderer.php` | Shared rendering helper for admin screens and Gutenberg dynamic blocks (template loading, escaping helpers). | `Core/Container.php` |

## 4. SI Framework / Premium — Licensing Boundary

Licensing chain (never bypassed):

```
SI Case Study Analysis Pro → LicenseController → Product-specific SDK →
LicenseState → FeatureManager → Premium Case Features
```

| File | Role | Depends On |
|---|---|---|
| `Premium/LicenseController.php` | Plugin-side orchestrator. Owns the License admin screen actions (activate/deactivate/verify), calls the SDK's `LicenseClient`, and writes results into `LicenseState`. This is the **only** class in the plugin allowed to call the SDK directly. | `Premium/SDK/*`, `LicenseState.php` |
| `Premium/LicenseClientInterface.php` | Interface the plugin codes against, so the concrete SDK implementation can be swapped/regenerated without touching plugin code. | — |
| `Premium/LicenseState.php` | Persists and exposes current license status (`active`, `inactive`, `expired`, etc.), licensed email, and entitlement flags read by `FeatureManager`. | `LicenseController.php` writes; `FeatureManager.php` reads |
| `Premium/SDK/si-sdk-loader.php` | Single `require_once` entry point for the auto-generated License SDK. | — |
| `Premium/SDK/si-sdk-config.php` | Returns this product's API base URL, namespace, product ID, and API key/secret. **Regenerated (not hand-edited) whenever the product's API key is rotated** via the SI License Manager Developer Tools → SDK Generator. | — |
| `Premium/SDK/includes/class-si-api-client.php` | Low-level HTTP client. Signs every request with `X-SI-Signature` / `X-SI-Timestamp` headers (HMAC-SHA256 over `METHOD\nROUTE\nTIMESTAMP\nSORTED_JSON_BODY`); the server rejects signatures older than 300 seconds. | `si-sdk-config.php` |
| `Premium/SDK/includes/class-si-license-client.php` | High-level `verify()`, `activate()`, `deactivate()`, `checkUpdate()` methods with response caching (verify results cached ~1 hour; pass `$useCache = false` to force a fresh check, e.g. immediately after activation). | `class-si-api-client.php` |
| `Premium/SDK/sample-integration.php` | Reference wiring (`admin_init` verification, activate/deactivate helper functions). Copy the relevant parts into `LicenseController.php` rather than requiring this file directly in production. | `si-sdk-loader.php`, `si-sdk-config.php` |

### 4.1 REST namespace consumed by the SDK

All licensing endpoints live under `/si-license-manager/v1/`: `POST /verify`, `POST /activate`, `POST /deactivate`, `POST /update`, `GET /download`, `GET /product`.

### 4.2 Licensing rules

- The API key in `si-sdk-config.php` identifies **the product**, not a customer — it is bundled into the distributed plugin and doubles as the HMAC signing secret. It is never a customer's license key.
- If the product's API key is rotated in the SI License Manager admin, **regenerate and redistribute the SDK**; the old key stops working immediately.
- Every SDK method returns `{ success|valid|activated|deactivated: bool, error: string|null }`. Code a single `if (! $result['success'])` branch — never assume success.
- `LicenseController` must remain resilient to an SDK/API change: if the generated SDK is regenerated after a key rotation, the plugin must keep working against `LicenseClientInterface` without code changes elsewhere in the plugin.

## 5. Modules — Case Intelligence Domain

Every folder under `includes/Modules/` follows the same internal shape, keeping Single Responsibility and enabling `EngineManager` to register modules uniformly:

```
Modules/<ModuleName>/
├── <ModuleName>Repository.php   — the only class allowed to touch $wpdb for this module
├── <ModuleName>Service.php      — business logic; called by Admin controllers, REST controllers, Gutenberg dynamic blocks
├── <ModuleName>Entity.php       — typed value object / model for one record
├── <ModuleName>RestController.php   — REST routes for this module (used by admin JS / block editor)
└── <ModuleName>AjaxController.php   — legacy/admin-page AJAX handlers, where still required
```

Module list (each maps 1:1 to a blueprint section):

| Module | Primary Entities | Notes |
|---|---|---|
| `Cases` | Case, CaseMetadata | Central object; every other module foreign-keys to a Case ID via `OwnershipManager` |
| `Context` | CaseContext | Background, industry/market/political environment, historical context |
| `Timeline` | TimelineEvent | Date, title, description, category, evidence link, importance |
| `Problems` | ProblemStatement, ProblemTreeNode | Symptom/Problem/Root Cause/Consequence chain |
| `Stakeholders` | Stakeholder | Interest/Influence/Impact/Position matrix |
| `Sources` | Source | Reusable source library — see §5.1 |
| `Evidence` | EvidenceRecord | Claim, source, reliability, citation, related finding |
| `Claims` | Claim | Claim-to-evidence relationship records |
| `Data` | DataTable, Measurement, KPI | Manual/CSV/XLSX/extracted quantitative case data |
| `Extraction` | ExtractionJob, ExtractedRecord | Full extraction pipeline — see §5.2 |
| `Frameworks` | FrameworkAnalysis | SWOT/PESTLE/Five Forces/Value Chain/7S; new frameworks plug in without touching `Cases` |
| `RootCause` | RootCauseRecord | 5 Whys, Fishbone, Cause-effect |
| `Alternatives` | Alternative | Candidate solutions with criteria/ratings |
| `Decisions` | DecisionMatrix, DecisionRecord | Weighted scoring, final decision, rationale |
| `Risks` | Risk | Probability, impact, severity, mitigation |
| `Recommendations` | Recommendation | Finding → Implication → Recommendation → Action → KPI |
| `Implementation` | ImplementationAction | Owner, start/end, status, KPI, dependencies |
| `Outcomes` | Outcome | Expected vs. actual per metric |
| `Findings` | Finding | Statement, evidence, analysis, impact, confidence, implication, source |
| `Lessons` | Lesson | Category, generalizable flag, related finding |
| `Publishing` | PublicationSnapshot, PublicationRecord | Blueprint selection → Gutenberg draft creation → publish tracking |

### 5.1 `Sources` module

Holds the reusable Source Library (independent of any single Case). `SourceRepository` stores title, publisher, source type, publication date, official URL, citation info, page/reference info, reliability level — this is the persistent record described in the Data Extraction Architecture (§5.2), decoupled from any temporary uploaded file.

### 5.2 `Extraction` module — Data Extraction, Persistence & Reuse pipeline

Implements the blueprint's core rule: **Source File ≠ Persistent Product Data.**

```
Modules/Extraction/
├── ExtractionRepository.php     — persists ExtractedRecord rows (metrics, tables, text, provenance)
├── ExtractionService.php        — orchestrates the pipeline below
├── Ingestion/
│   ├── TemporaryFileHandler.php     — stores an uploaded file in a temp location only
│   └── FileValidator.php            — validates format/size/integrity before parsing
├── Parsers/
│   ├── PdfParser.php
│   ├── XlsxParser.php
│   ├── CsvParser.php
│   └── ParserInterface.php
├── Normalization/
│   ├── NumericNormalizer.php        — currency/unit/number normalization
│   ├── DateNormalizer.php           — date/period normalization
│   └── MetricClassifier.php         — metric naming/category classification
├── Provenance/
│   └── ProvenanceRecorder.php       — writes source metadata + page/reference + extraction method + validation status alongside every persisted value
├── Lifecycle/
│   └── ExtractionStatus.php         — EXTRACTED / REVIEWED / VERIFIED / REJECTED / CORRECTED enum + transitions
└── Cleanup/
    └── TemporaryFileCleanup.php     — deletes the temp file only after successful validation + extraction + persistence + provenance recording; also runs on failure, interruption, timeout, or an abandoned upload
```

**Pipeline (implemented by `ExtractionService::run()`):**

```
EXTRACT → NORMALIZE → VALIDATE → PERSIST → REUSE → CLEANUP TEMPORARY SOURCE FILE
```

`ExtractionService` never deletes a temp file through `TemporaryFileCleanup` unless `ExtractionRepository::persist()` has already returned success **and** `ProvenanceRecorder` has recorded the source link. A failed or partial extraction must leave the temp file in place (or explicitly clean it up as a failure case — never mark it as successfully persisted).

Once persisted, records surface through `Evidence`, `Data`, and `Sources` modules — a single extraction is reusable across many Cases without a re-upload.

## 6. Gutenberg — Block Directory

`includes/Gutenberg/Blocks/` contains one folder per block from the blueprint's block library (§21.1 of the production blueprint): `CaseSummary/`, `CaseContext/`, `Problem/`, `Stakeholders/`, `Timeline/`, `Evidence/`, `CaseData/`, `RootCause/`, `SWOT/`, `PESTLE/`, `FiveForces/`, `SevenS/`, `Fishbone/`, `FiveWhys/`, `DecisionMatrix/`, `RiskMatrix/`, `Recommendation/`, `Implementation/`, `Outcome/`, `Lessons/`, `Source/`.

Each block folder:

| File | Role |
|---|---|
| `block.json` | Block metadata, attributes, supports (Block API v3 baseline). |
| `index.js` | Registers the block (`registerBlockType`). |
| `edit.js` | Editor UI — reads the relevant Module's REST endpoint for live case data. |
| `save.js` | Static markup for static blocks; omitted for dynamic blocks. |
| `render.php` | Server-side render callback for dynamic blocks — calls the module's `Service` layer, never `$wpdb` directly. |
| `style.css` | Front-end + editor shared styles. |
| `editor.css` | Editor-only styles. |

`Gutenberg/Patterns/` — reusable block patterns per Publication Blueprint (Academic, Business, HR, Leadership, Financial, Strategic, Comparative). `Gutenberg/Blueprints/` — blueprint-to-block-sequence mapping consumed when the user selects a blueprint in the Publication tab. `Gutenberg/Integration/` — `PublishingService` bridge that creates the native WP draft and pre-populates it with the selected blocks; never touches unrelated posts (Architecture Rule 9).

## 7. Admin — Screen Controllers

`includes/Admin/` mirrors the UI hierarchy in `CSAP_UI.md` 1:1. Each subfolder (`Dashboard/`, `Cases/`, `Sources/`, `Evidence/`, `Stakeholders/`, `Analysis/`, `Frameworks/`, `Decisions/`, `Risks/`, `Recommendations/`, `Findings/`, `Publishing/`, `License/`, `Settings/`, `Tools/`) contains a `*Controller.php` that:

1. Registers its menu/submenu page via `add_menu_page` / `add_submenu_page`.
2. Checks the relevant capability and nonce before rendering or handling any POST/AJAX action.
3. Calls into the matching Module's `Service` class for data — never a `Repository` directly.
4. Calls `FeatureManager::isEnabled()` to decide whether to render a premium section or an upsell notice.

`Admin/License/LicenseAdminController.php` is the only Admin controller that talks to `Premium/LicenseController.php`.

## 8. Load-Order Dependency Graph

```
si-case-study-analysis-pro.php
        │
        ▼
   manifest.php ──► Core/Requirements.php
        │
        ▼
   Core/Bootstrap.php
        │
        ├─► Core/Container.php
        ├─► Core/Loader.php
        └─► Core/Application.php
                │
                ▼
        Engine/EngineManager.php
                │
      ┌─────────┼──────────┐
      ▼         ▼          ▼
Engine/       Engine/    Premium/
Ownership     Feature    LicenseController.php
Manager.php   Manager.php     │
                │              ▼
                │        Premium/SDK/* → Premium/LicenseState.php
                └───────────────┘
                        │
                        ▼
               includes/Modules/*  (registered with EngineManager;
                                     every mutation routes through
                                     OwnershipManager)
                        │
          ┌─────────────┼─────────────┐
          ▼             ▼             ▼
   includes/Admin/*  includes/Gutenberg/*  REST/AJAX controllers
```

## 9. Root Files

| File | Role |
|---|---|
| `si-case-study-analysis-pro.php` | WordPress plugin header, constant definitions, requires `manifest.php` + `Core/Bootstrap.php`, registers `register_activation_hook` / `register_deactivation_hook`. |
| `uninstall.php` | Runs only on explicit uninstall (checks `WP_UNINSTALL_PLUGIN`); removes plugin options and, if the user opts in, plugin-owned database tables. Must never touch unrelated WordPress data. |
| `manifest.php` | Plugin metadata + module registry consumed by `Core/Manifest.php`. |
| `readme.txt` | Standard WordPress.org-style readme (even for a premium-distributed plugin, useful for changelog/versioning). |
