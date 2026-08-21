# FILE 1: CSAP_DETAILED_WORKPLAN.md
# SI Case Study Analysis Pro — Comprehensive Development Workplan

## Executive Summary

This workplan implements **SI Case Study Analysis Pro** following the locked architecture from the four governing documents. No deviation is permitted.

- **Total PHP Files:** 178
- **Development Phases:** 16 phases (aligned with Blueprint §35 Roadmap)
- **Architecture:** SI Framework v3.0
- **Publishing Layer:** Gutenberg-first (Native WordPress Posts)

---

## Development Principles (Non-Negotiable — from Blueprint §38 & §39)

1. Ownership before functionality — every mutation through `OwnershipManager`
2. `FeatureManager` is the entitlement gateway — never call SDK directly
3. Modules never access licensing SDK internals
4. Gutenberg is the publishing layer — no shortcode-only features
5. Case analysis is the intelligence layer
6. Evidence must be traceable to sources
7. AI cannot fabricate evidence
8. WordPress posts remain native
9. No automatic rewriting of unrelated posts
10. Blueprints never lock the user into a fixed article

**Final Inequalities (must always hold):**
- Case Intelligence ≠ Article Template
- Evidence ≠ Uploaded File
- Analysis ≠ Gutenberg Content
- Blueprint ≠ Locked Template
- AI ≠ Statistical/Case Truth
- Case ≠ WordPress Post
- Gutenberg = Publishing Layer
- SI Framework v3.0 = Application Foundation
- FeatureManager = Premium Entitlement Gateway
- LicenseController = Licensing Boundary
- OwnershipManager = Data Isolation Boundary
- Native WordPress = Publishing Foundation

---

## PHASE 1: Framework Foundation (Files #1–#13)

**Blueprint Reference:** Roadmap Phase 1 — SI Framework v3.0 Core, Container, Bootstrap, Loader, EngineManager, OwnershipManager, FeatureManager, LicenseController, product SDK integration

### 1.1 Root Files (3 files)

| # | File | Role |
|---|------|------|
| 1 | `si-case-study-analysis-pro.php` | Plugin entry — headers, constants, require manifest.php + Bootstrap.php, activation/deactivation hooks |
| 2 | `manifest.php` | Plugin metadata array (slug, version, min PHP/WP, module list) |
| 3 | `uninstall.php` | Opt-in destructive uninstall — only removes plugin data, never touches unrelated WP data |

### 1.2 Core Framework (6 files)

| # | File | Role | Depends On |
|---|------|------|------------|
| 4 | `Core/Requirements.php` | PHP/WP version + extension checks; halts boot on failure | manifest.php |
| 5 | `Core/Container.php` | Minimal DI container — service binding/resolution | — |
| 6 | `Core/Loader.php` | PSR-4-style autoloader wiring modules into Container | Container.php |
| 7 | `Core/Application.php` | Central app object; owns Container, exposes boot(), wires WP hooks | Container, Loader |
| 8 | `Core/Bootstrap.php` | Orchestrates boot: Requirements → Container → Loader → Application::boot() | All above |
| 9 | `Core/Manifest.php` | Runtime accessor for manifest.php data | manifest.php |

### 1.3 Engine Layer (4 files)

| # | File | Role | Depends On |
|---|------|------|------------|
| 10 | `Engine/EngineManager.php` | Registers and coordinates all Modules | Container |
| 11 | `Engine/OwnershipManager.php` | Mandatory mutation gateway: Request → Capability → Nonce → Validation → Ownership → Repository → Mutation | Container |
| 12 | `Engine/FeatureManager.php` | Single entitlement gateway wrapping LicenseState | LicenseState |
| 13 | `Engine/Renderer.php` | Shared rendering helper for admin screens and dynamic blocks | Container |

### Phase 1 Implementation Steps

1. Create plugin root file with proper WordPress plugin headers
2. Define constants: `SI_CSAP_VERSION`, `SI_CSAP_PLUGIN_DIR`, `SI_CSAP_PLUGIN_URL`, `SI_CSAP_TEXT_DOMAIN`
3. Implement `manifest.php` returning metadata array
4. Build `Requirements.php` — check PHP ≥ 7.4, WP ≥ 5.8, required extensions
5. Build `Container.php` — bind(), get(), has() methods with singleton support
6. Build `Loader.php` — PSR-4 autoloader for `SICSAP\` namespace
7. Build `Application.php` — owns Container, registers WP hooks via `init`, `admin_init`, `rest_api_init`
8. Build `Bootstrap.php` — lightweight orchestrator using appropriate hooks (not monolithic)
9. Build `Manifest.php` — runtime accessor
10. Build `EngineManager.php` — module registration with lazy loading
11. Build `OwnershipManager.php` — the critical mutation gateway
12. Build `FeatureManager.php` — wraps LicenseState for entitlement checks
13. Build `Renderer.php` — template loading + escaping helpers

### Phase 1 Testing Criteria

- [ ] Plugin activates without errors on WP 5.8+ / PHP 7.4+
- [ ] Deactivation preserves all data
- [ ] Uninstall removes only plugin-owned data
- [ ] All core classes load in correct dependency order
- [ ] Container resolves dependencies correctly
- [ ] OwnershipManager blocks unauthorized mutations
- [ ] Bootstrap is lightweight — no heavy operations on every request

---

## PHASE 2: Licensing & Premium Foundation (Files #14–#22)

**Blueprint Reference:** Roadmap Phase 1 (continued) — LicenseController, SDK integration

### 2.1 Premium Layer (8 files)

| # | File | Role |
|---|------|------|
| 14 | `Premium/LicenseController.php` | Plugin-side orchestrator — ONLY class allowed to call SDK directly |
| 15 | `Premium/LicenseClientInterface.php` | Interface the plugin codes against (SDK-swappable) |
| 16 | `Premium/LicenseState.php` | Persists license status, licensed email, entitlement flags |
| 17 | `Premium/SDK/si-sdk-loader.php` | Single require_once entry for SDK |
| 18 | `Premium/SDK/si-sdk-config.php` | API base URL, namespace, product ID, API key/secret |
| 19 | `Premium/SDK/includes/class-si-api-client.php` | HTTP client with HMAC-SHA256 signing |
| 20 | `Premium/SDK/includes/class-si-license-client.php` | verify(), activate(), deactivate(), checkUpdate() |
| 21 | `Premium/SDK/sample-integration.php` | Reference wiring (NOT used directly in production) |

### 2.2 Admin License Screen (1 file)

| # | File | Role |
|---|------|------|
| 22 | `Admin/License/LicenseAdminController.php` | License admin UI — activate/deactivate/verify |

### Phase 2 Implementation Steps

1. Create `LicenseClientInterface.php` defining SDK contract
2. Implement SDK files (api-client, license-client, config, loader)
3. Build `LicenseState.php` for status persistence (active/inactive/expired)
4. Create `LicenseController.php` orchestrating SDK calls
5. Implement `LicenseAdminController.php` with capability + nonce checks
6. Wire `FeatureManager` to read from `LicenseState`
7. Test full license lifecycle

### Phase 2 Testing Criteria

- [ ] License activation works
- [ ] Verification handles network failures gracefully (explicit error state, not silent "not licensed")
- [ ] Expired license shows correct state
- [ ] FeatureManager correctly gates premium features
- [ ] SDK regeneration doesn't break plugin (interface-based coding)
- [ ] No license keys or API secrets logged anywhere
- [ ] `sample-integration.php` NOT required directly in production paths

---

## PHASE 3: Cases Module Foundation (Files #23–#29)

**Blueprint Reference:** Roadmap Phase 2 — Cases, case metadata, case status, case ownership, case workspace

### 3.1 Cases Module (5 files)

| # | File | Role |
|---|------|------|
| 23 | `Modules/Cases/CaseEntity.php` | Case value object — all identity fields (Blueprint §7.2) |
| 24 | `Modules/Cases/CaseRepository.php` | ONLY class touching $wpdb for cases |
| 25 | `Modules/Cases/CaseService.php` | Business logic — called by controllers |
| 26 | `Modules/Cases/CaseRestController.php` | REST routes with permission_callback |
| 27 | `Modules/Cases/CaseAjaxController.php` | Legacy admin AJAX handlers |

### 3.2 Admin Cases Screens (2 files)

| # | File | Role |
|---|------|------|
| 28 | `Admin/Cases/CasesController.php` | Cases list screen (UI §3.1) |
| 29 | `Admin/Cases/CaseWorkspaceController.php` | 12-tab Case Workspace (UI §4) |

### Phase 3 Implementation Steps

1. Define `CaseEntity` with: Case ID, title, short title, case type, industry, organization, geography, period, analyst, status, confidentiality, tags, notes
2. Build `CaseRepository` with all CRUD using `$wpdb->prepare()`
3. Implement `CaseService` — create, edit, duplicate, archive, delete
4. Create `CaseRestController` — all routes with `permission_callback`
5. Build `CaseAjaxController` — nonce-verified handlers
6. Implement `CasesController.php` — list screen with search/filter/bulk actions
7. Create `CaseWorkspaceController.php` — 12-tab workspace UI
8. Register with `EngineManager`

### Phase 3 Testing Criteria

- [ ] Create/edit/duplicate/archive/delete cases
- [ ] Case isolation enforced — Case A never modifies Case B
- [ ] All mutations go through OwnershipManager
- [ ] REST endpoints verify capability + nonce
- [ ] Case workspace loads all 12 tabs
- [ ] **Case creation never automatically creates a WordPress post**

---

## PHASE 4: Context & Timeline Modules (Files #30–#39)

**Blueprint Reference:** Roadmap Phase 4 — Context, Timeline, Problem statement, Stakeholders

### 4.1 Context Module (5 files)

| # | File | Role |
|---|------|------|
| 30 | `Modules/Context/ContextEntity.php` | Background, industry, market, political environment |
| 31 | `Modules/Context/ContextRepository.php` | Persistence |
| 32 | `Modules/Context/ContextService.php` | Business logic |
| 33 | `Modules/Context/ContextRestController.php` | REST routes |
| 34 | `Modules/Context/ContextAjaxController.php` | AJAX handlers |

### 4.2 Timeline Module (5 files)

| # | File | Role |
|---|------|------|
| 35 | `Modules/Timeline/TimelineEntity.php` | Date, title, description, category, evidence link, importance |
| 36 | `Modules/Timeline/TimelineRepository.php` | Persistence |
| 37 | `Modules/Timeline/TimelineService.php` | Business logic |
| 38 | `Modules/Timeline/TimelineRestController.php` | REST routes |
| 39 | `Modules/Timeline/TimelineAjaxController.php` | AJAX handlers |

### Phase 4 Implementation Steps

1. Define `ContextEntity` — organization background, industry, market, economic, political/regulatory, structure, historical context
2. Build Context Repository/Service/Controllers
3. Define `TimelineEntity` — date, title, description, category, evidence, importance
4. Build Timeline Repository/Service/Controllers
5. Register both modules with EngineManager
6. Integrate into Case Workspace tabs (Context, Timeline within Overview)

### Phase 4 Testing Criteria

- [ ] Context saves/loads per case
- [ ] Timeline events CRUD works
- [ ] Timeline events link to evidence
- [ ] Both modules respect case ownership via OwnershipManager

---

## PHASE 5: Problems & Stakeholders Modules (Files #40–#49)

**Blueprint Reference:** Roadmap Phase 4 (continued)

### 5.1 Problems Module (5 files)

| # | File | Role |
|---|------|------|
| 40 | `Modules/Problems/ProblemEntity.php` | Symptom → Problem → Root Cause → Consequence chain |
| 41 | `Modules/Problems/ProblemRepository.php` | Persistence |
| 42 | `Modules/Problems/ProblemService.php` | Business logic |
| 43 | `Modules/Problems/ProblemRestController.php` | REST routes |
| 44 | `Modules/Problems/ProblemAjaxController.php` | AJAX handlers |

### 5.2 Stakeholders Module (5 files)

| # | File | Role |
|---|------|------|
| 45 | `Modules/Stakeholders/StakeholderEntity.php` | Interest/Influence/Impact/Position matrix |
| 46 | `Modules/Stakeholders/StakeholderRepository.php` | Persistence |
| 47 | `Modules/Stakeholders/StakeholderService.php` | Business logic |
| 48 | `Modules/Stakeholders/StakeholderRestController.php` | REST routes |
| 49 | `Modules/Stakeholders/StakeholderAjaxController.php` | AJAX handlers |

### Phase 5 Implementation Steps

1. Define `ProblemEntity` — problem statement, affected area, severity, urgency, evidence, impact, scope, assumptions
2. Build Problem Repository/Service/Controllers
3. Define `StakeholderEntity` — stakeholder, interest, influence, impact, position
4. Build Stakeholder Repository/Service/Controllers
5. Implement stakeholder matrix quadrant calculation
6. Register both modules
7. Integrate into Case Workspace Problem and Stakeholders tabs

### Phase 5 Testing Criteria

- [ ] Problem statement builder works
- [ ] Problem tree structure maintained
- [ ] Stakeholder CRUD works
- [ ] Influence/Interest matrix calculates correctly
- [ ] Problems link to evidence and stakeholders

---

## PHASE 6: Sources, Evidence & Claims Modules (Files #50–#67)

**Blueprint Reference:** Roadmap Phase 3 — Sources, Evidence, Claims, source metadata, evidence relationships

### 6.1 Sources Module (5 files)

| # | File | Role |
|---|------|------|
| 50 | `Modules/Sources/SourceEntity.php` | Reusable source — title, publisher, type, date, URL, citation, reliability |
| 51 | `Modules/Sources/SourceRepository.php` | Persistence (reusable across cases) |
| 52 | `Modules/Sources/SourceService.php` | Business logic |
| 53 | `Modules/Sources/SourceRestController.php` | REST routes |
| 54 | `Modules/Sources/SourceAjaxController.php` | AJAX handlers |

### 6.2 Evidence Module (5 files)

| # | File | Role |
|---|------|------|
| 55 | `Modules/Evidence/EvidenceEntity.php` | Claim, source, reliability, citation, related finding |
| 56 | `Modules/Evidence/EvidenceRepository.php` | Persistence |
| 57 | `Modules/Evidence/EvidenceService.php` | Business logic |
| 58 | `Modules/Evidence/EvidenceRestController.php` | REST routes |
| 59 | `Modules/Evidence/EvidenceAjaxController.php` | AJAX handlers |

### 6.3 Claims Module (5 files)

| # | File | Role |
|---|------|------|
| 60 | `Modules/Claims/ClaimEntity.php` | Claim-to-evidence relationship |
| 61 | `Modules/Claims/ClaimRepository.php` | Persistence |
| 62 | `Modules/Claims/ClaimService.php` | Business logic |
| 63 | `Modules/Claims/ClaimRestController.php` | REST routes |
| 64 | `Modules/Claims/ClaimAjaxController.php` | AJAX handlers |

### 6.4 Admin Sources Library (1 file)

| # | File | Role |
|---|------|------|
| 65 | `Admin/Sources/SourcesController.php` | Sources Library screen (UI §17) |
| 66 | `Admin/Evidence/EvidenceController.php` | Evidence admin helpers |
| 67 | `Admin/Stakeholders/StakeholdersController.php` | Stakeholder admin helpers |

### Phase 6 Implementation Steps

1. Define `SourceEntity` — reusable source library independent of any case
2. Build Source Repository/Service/Controllers
3. Define `EvidenceEntity` — evidence ID, case ID, claim, type, source, URL, citation, date, reliability, notes, related finding
4. Build Evidence Repository/Service/Controllers
5. Define `ClaimEntity` — claim-to-evidence relationship records
6. Build Claim Repository/Service/Controllers
7. Implement `SourcesController.php` — Sources Library with Overview/Citation/Evidence/Cases/References tabs
8. Register all three modules
9. Integrate into Case Workspace Evidence tab

### Phase 6 Testing Criteria

- [ ] Sources are reusable across multiple cases
- [ ] Evidence links to sources correctly
- [ ] Evidence reliability levels (High/Medium/Low) enforced
- [ ] Claims link to evidence — verifiable analytical chain
- [ ] Source provenance preserved even if file deleted
- [ ] Evidence coverage calculation works

---

## PHASE 7: Data & Extraction Modules (Files #68–#89)

**Blueprint Reference:** Roadmap Phase 3 (continued) + Data Extraction Architecture (§19)

### 7.1 Data Module (5 files)

| # | File | Role |
|---|------|------|
| 68 | `Modules/Data/DataEntity.php` | DataTable, Measurement, KPI |
| 69 | `Modules/Data/DataTableRepository.php` | Persistence |
| 70 | `Modules/Data/DataService.php` | Business logic |
| 71 | `Modules/Data/DataRestController.php` | REST routes |
| 72 | `Modules/Data/DataAjaxController.php` | AJAX handlers |

### 7.2 Extraction Module — Base (5 files)

| # | File | Role |
|---|------|------|
| 73 | `Modules/Extraction/ExtractionEntity.php` | ExtractionJob, ExtractedRecord |
| 74 | `Modules/Extraction/ExtractionRepository.php` | Persists extracted records + provenance |
| 75 | `Modules/Extraction/ExtractionService.php` | Pipeline orchestrator: EXTRACT → NORMALIZE → VALIDATE → PERSIST → REUSE → CLEANUP |
| 76 | `Modules/Extraction/ExtractionRestController.php` | REST routes |
| 77 | `Modules/Extraction/ExtractionAjaxController.php` | AJAX handlers |

### 7.3 Extraction Module — Ingestion (2 files)

| # | File | Role |
|---|------|------|
| 78 | `Modules/Extraction/Ingestion/TemporaryFileHandler.php` | Stores uploaded file in temp location only |
| 79 | `Modules/Extraction/Ingestion/FileValidator.php` | Validates format/size/integrity before parsing |

### 7.4 Extraction Module — Parsers (4 files)

| # | File | Role |
|---|------|------|
| 80 | `Modules/Extraction/Parsers/ParserInterface.php` | Common parser contract |
| 81 | `Modules/Extraction/Parsers/PdfParser.php` | PDF extraction |
| 82 | `Modules/Extraction/Parsers/XlsxParser.php` | XLSX/XLS extraction |
| 83 | `Modules/Extraction/Parsers/CsvParser.php` | CSV extraction |

### 7.5 Extraction Module — Normalization (3 files)

| # | File | Role |
|---|------|------|
| 84 | `Modules/Extraction/Normalization/NumericNormalizer.php` | Currency/unit/number normalization |
| 85 | `Modules/Extraction/Normalization/DateNormalizer.php` | Date/period normalization |
| 86 | `Modules/Extraction/Normalization/MetricClassifier.php` | Metric naming/category classification |

### 7.6 Extraction Module — Provenance, Lifecycle, Cleanup (3 files)

| # | File | Role |
|---|------|------|
| 87 | `Modules/Extraction/Provenance/ProvenanceRecorder.php` | Source metadata + page/reference + extraction method + validation status |
| 88 | `Modules/Extraction/Lifecycle/ExtractionStatus.php` | EXTRACTED/REVIEWED/VERIFIED/REJECTED/CORRECTED enum + transitions |
| 89 | `Modules/Extraction/Cleanup/TemporaryFileCleanup.php` | Deletes temp file ONLY after successful persist + provenance; also handles failure cases |

### Phase 7 Implementation Steps

1. Define `DataEntity` for tables/datasets/measurements/KPIs
2. Build Data Repository/Service/Controllers
3. Define `ExtractionEntity` for extraction jobs and records
4. Build `ExtractionRepository` — persists extracted data with provenance
5. Implement `TemporaryFileHandler` — temp storage only
6. Create `FileValidator` — format/size/integrity checks
7. Build `ParserInterface` and concrete parsers (PDF, XLSX, CSV)
8. Implement normalizers (numeric, date, metric classifier)
9. Create `ProvenanceRecorder` — every persisted value gets source link
10. Build `ExtractionStatus` lifecycle enum with transitions
11. Implement `TemporaryFileCleanup` — success AND failure cleanup
12. Create `ExtractionService` orchestrating the full pipeline
13. Register modules with EngineManager

### Phase 7 Testing Criteria

- [ ] Data tables CRUD works
- [ ] CSV/XLSX import works
- [ ] Extraction pipeline processes files correctly
- [ ] **Temp files cleaned up on success**
- [ ] **Temp files cleaned up on failure/timeout/abandonment**
- [ ] **Provenance recorded for ALL extracted data**
- [ ] Normalization works (currency, units, dates)
- [ ] Extracted data reusable across cases without re-upload
- [ ] Failed extraction NEVER marked as successfully persisted

---

## PHASE 8: Analytical Frameworks Module (Files #90–#96)

**Blueprint Reference:** Roadmap Phase 5 — SWOT, PESTLE, Five Forces, Value Chain, 7S

### 8.1 Frameworks Module (5 files)

| # | File | Role |
|---|------|------|
| 90 | `Modules/Frameworks/FrameworkEntity.php` | Framework analysis record |
| 91 | `Modules/Frameworks/FrameworkRepository.php` | Persistence |
| 92 | `Modules/Frameworks/FrameworkService.php` | Business logic |
| 93 | `Modules/Frameworks/FrameworkRestController.php` | REST routes |
| 94 | `Modules/Frameworks/FrameworkAjaxController.php` | AJAX handlers |

### 8.2 Admin Analysis Screens (2 files)

| # | File | Role |
|---|------|------|
| 95 | `Admin/Analysis/AnalysisController.php` | Analysis tab UI |
| 96 | `Admin/Frameworks/FrameworksController.php` | Framework picker UI |

### Phase 8 Implementation Steps

1. Define `FrameworkEntity` supporting multiple framework types via config registry
2. Build Framework Repository/Service/Controllers
3. Implement framework registry (config-based — new frameworks addable without touching Cases/EngineManager)
4. Implement SWOT, PESTLE, Five Forces, Value Chain, 7S analysis structures
5. Register module with EngineManager
6. Integrate into Case Workspace Analysis tab

### Phase 8 Testing Criteria

- [ ] All 5 frameworks work
- [ ] New frameworks addable without editing core
- [ ] Framework results link to findings
- [ ] Framework data respects case ownership

---

## PHASE 9: Root Cause Analysis Module (Files #97–#101)

**Blueprint Reference:** Roadmap Phase 6 — 5 Whys, Fishbone, Cause-effect

### 9.1 RootCause Module (5 files)

| # | File | Role |
|---|------|------|
| 97 | `Modules/RootCause/RootCauseEntity.php` | Root cause record |
| 98 | `Modules/RootCause/RootCauseRepository.php` | Persistence |
| 99 | `Modules/RootCause/RootCauseService.php` | Business logic |
| 100 | `Modules/RootCause/RootCauseRestController.php` | REST routes |
| 101 | `Modules/RootCause/RootCauseAjaxController.php` | AJAX handlers |

### Phase 9 Implementation Steps

1. Define `RootCauseEntity` supporting 5 Whys, Fishbone, Cause-effect
2. Build Repository/Service/Controllers
3. Implement 5 Whys chain logic
4. Implement Fishbone default categories (People, Process, Technology, Finance, Management, Environment, Policy, Market)
5. Register module with EngineManager

### Phase 9 Testing Criteria

- [ ] 5 Whys chain works
- [ ] Fishbone categories render correctly
- [ ] Root causes link to problems and evidence

---

## PHASE 10: Decision Intelligence Modules (Files #102–#118)

**Blueprint Reference:** Roadmap Phase 7 (Alternatives, Decisions) + Phase 8 (Risks)

### 10.1 Alternatives Module (5 files)

| # | File | Role |
|---|------|------|
| 102 | `Modules/Alternatives/AlternativeEntity.php` | Candidate solution with criteria/ratings |
| 103 | `Modules/Alternatives/AlternativeRepository.php` | Persistence |
| 104 | `Modules/Alternatives/AlternativeService.php` | Business logic |
| 105 | `Modules/Alternatives/AlternativeRestController.php` | REST routes |
| 106 | `Modules/Alternatives/AlternativeAjaxController.php` | AJAX handlers |

### 10.2 Decisions Module (5 files)

| # | File | Role |
|---|------|------|
| 107 | `Modules/Decisions/DecisionEntity.php` | Decision matrix, final decision, rationale |
| 108 | `Modules/Decisions/DecisionRepository.php` | Persistence |
| 109 | `Modules/Decisions/DecisionService.php` | Business logic |
| 110 | `Modules/Decisions/DecisionRestController.php` | REST routes |
| 111 | `Modules/Decisions/DecisionAjaxController.php` | AJAX handlers |

### 10.3 Risks Module (5 files)

| # | File | Role |
|---|------|------|
| 112 | `Modules/Risks/RiskEntity.php` | Probability, impact, severity, mitigation |
| 113 | `Modules/Risks/RiskRepository.php` | Persistence |
| 114 | `Modules/Risks/RiskService.php` | Business logic |
| 115 | `Modules/Risks/RiskRestController.php` | REST routes |
| 116 | `Modules/Risks/RiskAjaxController.php` | AJAX handlers |

### 10.4 Admin Decision Screens (2 files)

| # | File | Role |
|---|------|------|
| 117 | `Admin/Decisions/DecisionsController.php` | Decision tab UI |
| 118 | `Admin/Risks/RisksController.php` | Risk matrix UI |

### Phase 10 Implementation Steps

1. Define `AlternativeEntity` — candidate options with pros/cons
2. Build Alternative Repository/Service/Controllers
3. Define `DecisionEntity` — weighted scoring (Cost/Impact/Risk/Feasibility/Score)
4. Build Decision Repository/Service/Controllers
5. Implement weighted scoring calculation
6. Define `RiskEntity` — probability × impact × severity × mitigation
7. Build Risk Repository/Service/Controllers
8. Implement risk matrix calculation
9. Register all three modules
10. Integrate into Case Workspace Decision tab

### Phase 10 Testing Criteria

- [ ] Alternatives CRUD works
- [ ] Decision matrix calculates scores correctly
- [ ] Risk matrix renders (probability × impact)
- [ ] Decisions link to alternatives and risks

---

## PHASE 11: Recommendations & Implementation Modules (Files #119–#129)

**Blueprint Reference:** Roadmap Phase 9 — Recommendations, Implementation

### 11.1 Recommendations Module (5 files)

| # | File | Role |
|---|------|------|
| 119 | `Modules/Recommendations/RecommendationEntity.php` | Finding → Implication → Recommendation → Action → KPI |
| 120 | `Modules/Recommendations/RecommendationRepository.php` | Persistence |
| 121 | `Modules/Recommendations/RecommendationService.php` | Business logic |
| 122 | `Modules/Recommendations/RecommendationRestController.php` | REST routes |
| 123 | `Modules/Recommendations/RecommendationAjaxController.php` | AJAX handlers |

### 11.2 Implementation Module (5 files)

| # | File | Role |
|---|------|------|
| 124 | `Modules/Implementation/ImplementationEntity.php` | Action, owner, start/end, status, KPI, dependencies |
| 125 | `Modules/Implementation/ImplementationRepository.php` | Persistence |
| 126 | `Modules/Implementation/ImplementationService.php` | Business logic |
| 127 | `Modules/Implementation/ImplementationRestController.php` | REST routes |
| 128 | `Modules/Implementation/ImplementationAjaxController.php` | AJAX handlers |

### 11.3 Admin Recommendations Screen (1 file)

| # | File | Role |
|---|------|------|
| 129 | `Admin/Recommendations/RecommendationsController.php` | Recommendations tab UI |

### Phase 11 Implementation Steps

1. Define `RecommendationEntity` — the core chain: Finding → Implication → Recommendation → Action → KPI
2. Build Recommendation Repository/Service/Controllers
3. Define `ImplementationEntity` — action, owner, start, end, status, KPI, resources, dependencies, risks
4. Build Implementation Repository/Service/Controllers
5. Register both modules
6. Integrate into Case Workspace Recommendations and Implementation tabs

### Phase 11 Testing Criteria

- [ ] Recommendation chain works end-to-end
- [ ] Recommendations link to findings and evidence
- [ ] Implementation actions CRUD works
- [ ] KPIs track correctly

---

## PHASE 12: Findings, Outcomes & Lessons Modules (Files #130–#145)

**Blueprint Reference:** Roadmap Phase 10 — Findings, Lessons

### 12.1 Findings Module (5 files)

| # | File | Role |
|---|------|------|
| 130 | `Modules/Findings/FindingEntity.php` | Statement, evidence, analysis, impact, confidence, implication |
| 131 | `Modules/Findings/FindingRepository.php` | Persistence |
| 132 | `Modules/Findings/FindingService.php` | Business logic |
| 133 | `Modules/Findings/FindingRestController.php` | REST routes |
| 134 | `Modules/Findings/FindingAjaxController.php` | AJAX handlers |

### 12.2 Outcomes Module (5 files)

| # | File | Role |
|---|------|------|
| 135 | `Modules/Outcomes/OutcomeEntity.php` | Expected vs. actual per metric |
| 136 | `Modules/Outcomes/OutcomeRepository.php` | Persistence |
| 137 | `Modules/Outcomes/OutcomeService.php` | Business logic |
| 138 | `Modules/Outcomes/OutcomeRestController.php` | REST routes |
| 139 | `Modules/Outcomes/OutcomeAjaxController.php` | AJAX handlers |

### 12.3 Lessons Module (5 files)

| # | File | Role |
|---|------|------|
| 140 | `Modules/Lessons/LessonEntity.php` | Category, generalizable flag, related finding |
| 141 | `Modules/Lessons/LessonRepository.php` | Persistence |
| 142 | `Modules/Lessons/LessonService.php` | Business logic |
| 143 | `Modules/Lessons/LessonRestController.php` | REST routes |
| 144 | `Modules/Lessons/LessonAjaxController.php` | AJAX handlers |

### 12.4 Admin Findings Screen (1 file)

| # | File | Role |
|---|------|------|
| 145 | `Admin/Findings/FindingsController.php` | Findings UI |

### Phase 12 Implementation Steps

1. Define `FindingEntity` — statement, evidence links, analysis, impact, confidence, implication, source
2. Build Finding Repository/Service/Controllers
3. Define `OutcomeEntity` — expected vs. actual per metric
4. Build Outcome Repository/Service/Controllers
5. Define `LessonEntity` — statement, category, generalizable, related finding
6. Build Lesson Repository/Service/Controllers
7. Register all three modules
8. Integrate into Case Workspace Outcome & Lessons tab

### Phase 12 Testing Criteria

- [ ] Findings link to evidence correctly
- [ ] Evidence coverage calculation works
- [ ] Outcomes track expected vs. actual
- [ ] Lessons mark generalizable flag correctly

---

## PHASE 13: Publishing Module & Gutenberg Blocks (Files #146–#175)

**Blueprint Reference:** Roadmap Phase 11 (Gutenberg) + Phase 12 (Publication Blueprints)

### 13.1 Publishing Module (5 files)

| # | File | Role |
|---|------|------|
| 146 | `Modules/Publishing/PublicationEntity.php` | PublicationSnapshot, PublicationRecord |
| 147 | `Modules/Publishing/PublicationRepository.php` | Persistence |
| 148 | `Modules/Publishing/PublicationService.php` | Business logic — creates native WP draft |
| 149 | `Modules/Publishing/PublicationRestController.php` | REST routes |
| 150 | `Modules/Publishing/PublicationAjaxController.php` | AJAX handlers |

### 13.2 Gutenberg Integration (3 files)

| # | File | Role |
|---|------|------|
| 151 | `Gutenberg/Integration/PublishingServiceBridge.php` | Creates native WP draft, pre-populates blocks |
| 152 | `Gutenberg/Integration/BlockRegistrar.php` | Registers all 21 blocks |
| 153 | `Gutenberg/Integration/BlueprintLoader.php` | Blueprint-to-block-sequence mapping |

### 13.3 Gutenberg Block render.php Files (21 files)

| # | File | Block |
|---|------|-------|
| 154 | `Gutenberg/Blocks/CaseSummary/render.php` | SI Case Summary |
| 155 | `Gutenberg/Blocks/CaseContext/render.php` | SI Case Context |
| 156 | `Gutenberg/Blocks/Problem/render.php` | SI Problem |
| 157 | `Gutenberg/Blocks/Stakeholders/render.php` | SI Stakeholders |
| 158 | `Gutenberg/Blocks/Timeline/render.php` | SI Timeline |
| 159 | `Gutenberg/Blocks/Evidence/render.php` | SI Evidence |
| 160 | `Gutenberg/Blocks/CaseData/render.php` | SI Case Data |
| 161 | `Gutenberg/Blocks/RootCause/render.php` | SI Root Cause |
| 162 | `Gutenberg/Blocks/SWOT/render.php` | SI SWOT |
| 163 | `Gutenberg/Blocks/PESTLE/render.php` | SI PESTLE |
| 164 | `Gutenberg/Blocks/FiveForces/render.php` | SI Five Forces |
| 165 | `Gutenberg/Blocks/SevenS/render.php` | SI 7S |
| 166 | `Gutenberg/Blocks/Fishbone/render.php` | SI Fishbone |
| 167 | `Gutenberg/Blocks/FiveWhys/render.php` | SI 5 Whys |
| 168 | `Gutenberg/Blocks/DecisionMatrix/render.php` | SI Decision Matrix |
| 169 | `Gutenberg/Blocks/RiskMatrix/render.php` | SI Risk Matrix |
| 170 | `Gutenberg/Blocks/Recommendation/render.php` | SI Recommendation |
| 171 | `Gutenberg/Blocks/Implementation/render.php` | SI Implementation |
| 172 | `Gutenberg/Blocks/Outcome/render.php` | SI Outcome |
| 173 | `Gutenberg/Blocks/Lessons/render.php` | SI Lessons |
| 174 | `Gutenberg/Blocks/Source/render.php` | SI Case Source |

### 13.4 Admin Publishing Screen (1 file)

| # | File | Role |
|---|------|------|
| 175 | `Admin/Publishing/PublishingController.php` | Publication tab UI (UI §16) |

### Phase 13 Implementation Steps

1. Define `PublicationEntity` — blueprint selection, snapshot, WP post link
2. Build Publication Repository/Service/Controllers
3. Implement `PublishingServiceBridge` — creates native WP draft, NEVER touches unrelated posts
4. Create `BlockRegistrar` — registers all 21 blocks via Block API v3
5. Implement `BlueprintLoader` — blueprint-to-block-sequence mapping
6. Create `render.php` for each of 21 blocks — dynamic blocks calling Service layer, never $wpdb directly
7. Implement `PublishingController.php` — Publication tab with blueprint selection
8. Register Publishing module with EngineManager

### Phase 13 Testing Criteria

- [ ] All 21 blocks register correctly
- [ ] Blocks render case data via Service layer
- [ ] Publication creates NATIVE WordPress draft
- [ ] Gutenberg editor opens draft correctly
- [ ] User can freely reorder/edit blocks
- [ ] **CRITICAL: Existing Posts A, B, C remain completely unchanged**
- [ ] No automatic rewriting of unrelated posts

---

## PHASE 14: Dashboard, Tools & Settings (Files #176–#178)

**Blueprint Reference:** Roadmap Phase 16 (partial) — Admin screens

### 14.1 Admin Screens (3 files)

| # | File | Role |
|---|------|------|
| 176 | `Admin/Dashboard/DashboardController.php` | Dashboard with statistics, recent cases, evidence health |
| 177 | `Admin/Tools/ToolsController.php` | Import, extraction, maintenance, utilities |
| 178 | `Admin/Settings/SettingsController.php` | 11 settings tabs |

### Phase 14 Implementation Steps

1. Implement `DashboardController.php` — statistics, quick actions, recent activity
2. Create `ToolsController.php` — import/export/extraction/cleanup/health tools
3. Implement `SettingsController.php` — General, Cases, Evidence, Data, Analysis, Publishing, Gutenberg, AI, SEO, Security, Advanced tabs
4. Wire dashboard to case/evidence/publication statistics

### Phase 14 Testing Criteria

- [ ] Dashboard shows correct statistics
- [ ] Tools screen works
- [ ] Settings save correctly per tab
- [ ] All admin menus in correct order (UI §1)

---

## PHASE 15: Security Hardening & Integration Testing

**Blueprint Reference:** Roadmap Phase 15 — Security

### Tasks (No new files — hardening existing files)

1. **Capability Checks** — verify every endpoint
2. **Nonce Verification** — every mutation path
3. **Sanitization** — never save raw input
4. **SQL Injection Prevention** — all queries use `$wpdb->prepare()`
5. **Output Escaping** — all output escaped
6. **Ownership Enforcement** — all mutations through OwnershipManager
7. **Upload Security** — file validation, temp handling
8. **Case Isolation** — Case A never affects Case B
9. **Post Isolation** — critical regression test

---

## PHASE 16: Production QA

**Blueprint Reference:** Roadmap Phase 16 + §36 Production QA Checklist

### Checklist

- [ ] **Installation:** activation, deactivation, uninstall, upgrade
- [ ] **License:** activation, verification, deactivation, invalid key, expired state, network failure, SDK failure
- [ ] **Case:** create, edit, duplicate, archive, delete
- [ ] **Evidence:** create, update, source linking, claim linking
- [ ] **Analysis:** frameworks, root cause, alternatives, decisions, risks
- [ ] **Publishing:** create draft, Gutenberg editing, revisions, categories, tags, author, featured image, SEO compatibility
- [ ] **CRITICAL REGRESSION:** Given existing Posts A, B, C → Create Case X → Analyze → Create draft → Posts A, B, C unchanged
- [ ] **Performance:** lazy loading, no unnecessary queries
- [ ] **Security:** all checks pass

---

## FILE COUNT SUMMARY

| Category | Count |
|----------|-------|
| Root Files | 3 |
| Core Framework | 6 |
| Engine Layer | 4 |
| Premium/Licensing (incl. SDK) | 8 |
| Modules — Base (21 modules × 5 files) | 105 |
| Modules — Extraction Additional | 12 |
| Admin Controllers | 16 |
| Gutenberg Integration | 3 |
| Gutenberg Block render.php | 21 |
| **TOTAL PHP FILES** | **178** |

---

## STATUS: ⏸️ WAITING FOR APPROVAL

All 178 PHP files are listed and mapped to their phases. No code has been generated yet. Awaiting your approval to begin Phase 1 implementation.