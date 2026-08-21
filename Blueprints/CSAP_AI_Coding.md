# CSAP AI Coding Guideline

For any AI coding assistant (Claude Code or otherwise) working on the **SI Case Study Analysis Pro** codebase. Read this alongside `SI_CSAP_Production_blueprint.md`, `CSAP_php_directory.md`, and `CSAP_UI.md` before writing or modifying any file. If an instruction here conflicts with a chat request, this file and the blueprint win unless the user explicitly overrides them in writing for that task.

---

## 1. Before Writing Any Code

1. Identify which **Development Roadmap phase** (blueprint §35) the task belongs to, and which **Module** (php directory §5) owns the data involved.
2. Confirm the change doesn't violate any of the 10 **Architecture Rules** (blueprint §38) or the **Final Development Rule** inequalities (blueprint §39).
3. Check whether the file already exists in `CSAP_php_directory.md`'s tree — extend the existing Repository/Service/Entity pattern for that module rather than inventing a new shape.
4. If the task isn't covered by the blueprint or would require deviating from it (e.g. a new top-level admin menu, a shortcode-based feature, permanent document storage), **stop and ask** rather than improvising — do not silently reinterpret the architecture to make a request easier.

## 2. Non-Negotiable Architecture Rules

These come directly from the production blueprint and must never be violated by generated code, no matter how the request is phrased:

1. **Ownership before functionality** — every mutation goes through `OwnershipManager`; no Repository is written to any other way.
2. **`FeatureManager` is the entitlement gateway** — modules and Admin controllers call `FeatureManager::isEnabled()`, never `LicenseController` or the SDK directly.
3. **Modules never access licensing SDK internals.** Only `Premium/LicenseController.php` calls into `Premium/SDK/*`.
4. **Gutenberg is the publishing layer** — no shortcode-only features, no custom article editor, no drag-and-drop template builder.
5. **Case analysis is the intelligence layer** — Case data and structure never depend on how it will eventually be published.
6. **Evidence must be traceable to sources** — never persist a Finding, Claim, or Metric without a source/provenance link where the blueprint requires one (§12, §19.7).
7. **AI cannot fabricate evidence, sources, facts, financial figures, interviews, or outcomes**, and must never present assumptions as facts (§24). This applies to AI features built *into* the plugin, and to this assistant's own generated content, comments, and sample data — don't invent citations or numbers to fill an example.
8. **WordPress posts remain native** — the plugin must not replace WordPress's own publishing system (editor, revisions, taxonomies, scheduling).
9. **No automatic rewriting of unrelated posts** — publishing is always explicitly user-initiated (`Case → Create Draft → User Reviews → Publish`). Any code path that could touch a post not created by this specific publish action is a bug.
10. **Blueprints never lock the user into a fixed article** — Publication Blueprints seed a block sequence; the user must always be able to freely edit it afterward.

Additional standing inequalities to keep true in every diff: `Case ≠ WordPress Post`, `Evidence ≠ Uploaded File`, `Analysis ≠ Gutenberg Content`, `Blueprint ≠ Locked Template`, `AI ≠ Statistical/Case Truth`.

## 3. Coding Standards (Priority: High)

Always follow, in every PHP file generated or edited:

- **WordPress Coding Standards (WPCS)**
- **Single Responsibility Principle** — one Repository per module, one Service per module; don't merge concerns
- **Modular Architecture** — new Modules and Frameworks must be addable without editing the core `Cases`/`EngineManager` classes
- **PSR-4 Autoloading**
- **Object-Oriented Programming**, **Dependency Injection** (via `Core/Container.php`, never `new` a dependency inline in a Service/Controller)
- **SOLID Principles**
- **Typed properties and return types** on every method
- **Proper Hook Usage** (correct hook names/priorities, no premature or duplicate registration)
- **Internationalization (i18n)** — every user-facing string wrapped in the plugin's text domain
- **Versioned Asset Management** — enqueue CSS/JS with the plugin version string, not hardcoded/unversioned
- **Consistent Naming & Prefixing** — `si_csap_` / `SICSAP_`-style prefixes for functions, options, hooks, and DB tables to avoid collisions
- **PHPDoc** on every class and public method
- **Activation, Deactivation & Uninstall Separation** — these are three distinct code paths; deactivation must not delete data, uninstall must be opt-in destructive and only via `uninstall.php`
- **DRY**, **KISS**, **YAGNI**
- **Consistent File & Directory Structure** — match `CSAP_php_directory.md` exactly; don't introduce ad-hoc folders
- **Configuration over Hardcoding** — feature flags, framework definitions, and blueprint sequences belong in config/registry arrays, not inline conditionals
- **Performance-Conscious Loading** — load module classes lazily via the Container/Loader, not all on every request
- **Semantic Versioning**

## 4. Security Guideline (Always Use)

Every new PHP file starts with:

```php
<?php

declare(strict_types=1);

namespace SICSAP\...;

defined('ABSPATH') || exit;
```

Mandatory in every request-handling path:

- **Sanitize input** — never save raw user input; sanitize with the correct WordPress function for the data type.
- **Always declare parameter and return types.**
- **Nonce verification** — reject any request with an invalid or missing nonce.
- **Always use `$wpdb->prepare()`.** Never concatenate SQL strings with user input.
- **SQL injection prevention** applies to every Repository query, including dynamic `ORDER BY`/`IN` clauses.
- **Never echo raw JavaScript variables** — escape/JSON-encode before output.
- **Every AJAX and REST endpoint must verify capability + nonce/permission callback** before touching a Repository. A missing `permission_callback` on a REST route, or a missing capability check in an AJAX handler, is a blocking defect, not a style nit.
- Capability checks and ownership checks are separate and both required: a user may have the general capability to manage cases but must still be blocked from mutating a case they don't own — that's what `OwnershipManager` enforces (§2, Rule 1).

## 5. Licensing SDK Rules

- Only `Premium/LicenseController.php` may `require` or call into `Premium/SDK/*`. Never call the SDK's `ApiClient`/`LicenseClient` from a Module, Admin controller, or Gutenberg block.
- Code the plugin against `Premium/LicenseClientInterface.php`, not the concrete SDK class, so the plugin **stays stable when the SDK is regenerated** after an API key rotation (the blueprint's explicit requirement: *"whenever a new API/new key changes, the plugin must remain stable"*).
- Never hardcode a license key, API key, or HMAC secret anywhere outside `si-sdk-config.php`. Never log a license key or the API signing secret.
- Treat every SDK response uniformly: check the `success`/`valid`/`activated`/`deactivated` boolean and the `error` key — never assume success and never let a network failure be silently treated as "not licensed" without surfacing an explicit error state.
- Do not use `sample-integration.php` directly in production code paths — port the relevant logic into `LicenseController.php`.
- `FeatureManager` reads only from `LicenseState`. If a feature check needs something the SDK doesn't currently expose, extend `LicenseState`, not the feature-check call sites.

## 6. Data Extraction Rules

When touching anything in `Modules/Extraction/`:

- Treat every uploaded file as **temporary ingestion media**, never a permanent asset, unless the user has explicitly opted to retain it.
- The pipeline order is fixed: `EXTRACT → NORMALIZE → VALIDATE → PERSIST → REUSE → CLEANUP`. Do not delete the temp file before persistence and provenance recording both succeed.
- Every persisted metric/table/fact must carry a provenance record (source, source type, official-source flag, URL, page/reference, extraction method, validation status) — never persist a bare number without its source link.
- A failed, partial, or interrupted extraction must never be marked as successfully persisted, and its temp file must still be cleaned up on failure/timeout/abandonment — don't leave orphaned temp files as the default failure mode.
- Normalization (currency, unit, date/period, metric naming) happens before persistence, and the normalized record must retain a pointer back to the original raw value and source.
- Extraction records follow the lifecycle `EXTRACTED → REVIEWED → VERIFIED` (or `REJECTED` / `CORRECTED`) — don't collapse this into a single boolean "done" flag.

## 7. AI-Feature Implementation Rules (for AI features inside the plugin)

When building the in-product AI Assistance features (blueprint §24, Phase 13):

- AI output is always a **suggestion**, never an auto-committed record. Every AI-drafted finding, recommendation, framework suggestion, or summary must land in a reviewable/editable state before it can be attached to the Case as confirmed content.
- Never wire an AI response directly into `OwnershipManager`'s mutation path without a human-confirmation step in between.
- AI evidence workflow must always be `AI → Evidence → Suggested Interpretation → Human Review → Confirmed Finding → Publication`, never `AI → Unverified Claim → Automatic Publication`.
- Do not build AI features that summarize/compare across Cases the current user doesn't own — route all AI context through the same ownership-checked read paths as everything else.

## 8. Gutenberg Implementation Rules

- One block per analytical concept (blueprint §21.1) — never merge multiple concepts into a single monolithic "Case Study" block.
- Use Block API v3 conventions; dynamic blocks call the owning Module's `Service`, never `$wpdb` directly from `render.php`.
- No shortcode-only feature paths for primary functionality; shortcodes are compatibility-only, if present at all.
- Blueprints (`Gutenberg/Blueprints/`) only seed an initial block sequence — never generate a block sequence that can't subsequently be freely reordered/edited by the user.

## 9. Testing & Regression Requirements

Any change touching Publishing, Cases, or Ownership must be validated (manually or with an automated test) against the **critical regression scenario**:

```
Given existing WordPress Posts A, B, C
When Case X is created, analyzed, and a draft is created from it
Then Post A, Post B, and Post C are unchanged
```

Also re-check, as relevant to the change:

- Case isolation — Case A mutations never affect Case B.
- License lifecycle — activation, verification, deactivation, invalid key, expired state, network failure, SDK failure all resolve to a defined, non-crashing state.
- Temp-file cleanup on both success and every documented failure mode (§6).
- Capability + nonce + ownership checks on every new AJAX/REST endpoint (§4).

## 10. What to Refuse or Push Back On

If a request would do any of the following, implement the safe/architecturally-correct version instead and say so, rather than complying literally:

- Auto-modifying, deleting, or bulk-rewriting WordPress posts the plugin didn't create as part of the current explicit publish action.
- Adding a new top-level admin menu for something that belongs inside the Case Workspace (Evidence, Analysis, Risks, Recommendations, Findings, Decisions).
- Persisting an uploaded source file permanently by default instead of following the extract-then-cleanup pipeline.
- Hardcoding or logging a license key, API key, or HMAC signing secret.
- Bypassing `OwnershipManager` or `FeatureManager` "just for this one endpoint" for convenience or speed.
- Having an AI-assist feature write directly to a Case record without a human-review step.
- Building a fixed, non-editable article template instead of an optional, freely-editable Publication Blueprint.

## 11. Working Style

- Match the existing Repository/Service/Entity/Controller pattern per module (php directory §5) instead of introducing a new pattern for a single feature.
- Prefer extending an existing Module over creating a new one; only create a new Module folder when the concept genuinely doesn't belong to any existing one in §5 of `CSAP_php_directory.md`.
- Keep framework definitions (SWOT/PESTLE/Five Forces/Value Chain/7S and any future framework) in configuration/registry form so new frameworks are addable without editing `Cases` or `EngineManager` (Roadmap Phase 5 requirement).
- When in doubt about scope, implement the smallest change that satisfies the current Roadmap phase rather than pulling forward work from a later phase.
