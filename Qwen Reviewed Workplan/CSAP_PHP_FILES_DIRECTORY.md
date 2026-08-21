# FILE 2: CSAP_PHP_FILES_DIRECTORY.md
# SI Case Study Analysis Pro — Complete PHP Files Directory

## Plugin Root Base Path
`si-case-study-analysis-pro/`

All paths below are relative to this root.

---

## 1. ROOT FILES (3 files)

| # | File Path | Role | Phase |
|---|-----------|------|-------|
| 1 | `si-case-study-analysis-pro.php` | Plugin entry — headers, constants, requires manifest.php + Bootstrap.php, activation/deactivation hooks | 1 |
| 2 | `manifest.php` | Plugin metadata array (slug, version, min PHP/WP, module list) | 1 |
| 3 | `uninstall.php` | Opt-in destructive uninstall — removes only plugin data | 1 |

---

## 2. SI FRAMEWORK / CORE (6 files)

| # | File Path | Role | Phase |
|---|-----------|------|-------|
| 4 | `includes/SI Framework/Core/Requirements.php` | PHP/WP version + extension checks; halts boot on failure | 1 |
| 5 | `includes/SI Framework/Core/Container.php` | Minimal DI container — bind(), get(), has() | 1 |
| 6 | `includes/SI Framework/Core/Loader.php` | PSR-4 autoloader wiring modules into Container | 1 |
| 7 | `includes/SI Framework/Core/Application.php` | Central app object; owns Container, boot(), WP hooks | 1 |
| 8 | `includes/SI Framework/Core/Bootstrap.php` | Boot orchestrator: Requirements → Container → Loader → Application | 1 |
| 9 | `includes/SI Framework/Core/Manifest.php` | Runtime accessor for manifest.php data | 1 |

---

## 3. SI FRAMEWORK / ENGINE (4 files)

| # | File Path | Role | Phase |
|---|-----------|------|-------|
| 10 | `includes/SI Framework/Engine/EngineManager.php` | Registers and coordinates all Modules | 1 |
| 11 | `includes/SI Framework/Engine/OwnershipManager.php` | Mandatory mutation gateway | 1 |
| 12 | `includes/SI Framework/Engine/FeatureManager.php` | Single entitlement gateway wrapping LicenseState | 2 |
| 13 | `includes/SI Framework/Engine/Renderer.php` | Shared rendering helper | 1 |

---

## 4. SI FRAMEWORK / PREMIUM — LICENSING BOUNDARY (8 files)

| # | File Path | Role | Phase |
|---|-----------|------|-------|
| 14 | `includes/SI Framework/Premium/LicenseController.php` | Plugin-side orchestrator — ONLY class calling SDK directly | 2 |
| 15 | `includes/SI Framework/Premium/LicenseClientInterface.php` | Interface for SDK abstraction | 2 |
| 16 | `includes/SI Framework/Premium/LicenseState.php` | Persists license status + entitlement flags | 2 |
| 17 | `includes/SI Framework/Premium/SDK/si-sdk-loader.php` | Single require_once entry for SDK | 2 |
| 18 | `includes/SI Framework/Premium/SDK/si-sdk-config.php` | API base URL, namespace, product ID, API key/secret | 2 |
| 19 | `includes/SI Framework/Premium/SDK/includes/class-si-api-client.php` | HTTP client with HMAC-SHA256 signing | 2 |
| 20 | `includes/SI Framework/Premium/SDK/includes/class-si-license-client.php` | verify(), activate(), deactivate(), checkUpdate() | 2 |
| 21 | `includes/SI Framework/Premium/SDK/sample-integration.php` | Reference wiring (NOT used directly in production) | 2 |

---

## 5. MODULES — CASE INTELLIGENCE DOMAIN

### 5.1 Cases Module (5 files)

| # | File Path | Role | Phase |
|---|-----------|------|-------|
| 22 | `includes/Modules/Cases/CaseEntity.php` | Case value object — identity fields | 3 |
| 23 | `includes/Modules/Cases/CaseRepository.php` | ONLY $wpdb access for cases | 3 |
| 24 | `includes/Modules/Cases/CaseService.php` | Business logic | 3 |
| 25 | `includes/Modules/Cases/CaseRestController.php` | REST routes | 3 |
| 26 | `includes/Modules/Cases/CaseAjaxController.php` | AJAX handlers | 3 |

### 5.2 Context Module (5 files)

| # | File Path | Role | Phase |
|---|-----------|------|-------|
| 27 | `includes/Modules/Context/ContextEntity.php` | Background, industry, market, environment | 4 |
| 28 | `includes/Modules/Context/ContextRepository.php` | Persistence | 4 |
| 29 | `includes/Modules/Context/ContextService.php` | Business logic | 4 |
| 30 | `includes/Modules/Context/ContextRestController.php` | REST routes | 4 |
| 31 | `includes/Modules/Context/ContextAjaxController.php` | AJAX handlers | 4 |

### 5.3 Timeline Module (5 files)

| # | File Path | Role | Phase |
|---|-----------|------|-------|
| 32 | `includes/Modules/Timeline/TimelineEntity.php` | Date, title, description, category, evidence, importance | 4 |
| 33 | `includes/Modules/Timeline/TimelineRepository.php` | Persistence | 4 |
| 34 | `includes/Modules/Timeline/TimelineService.php` | Business logic | 4 |
| 35 | `includes/Modules/Timeline/TimelineRestController.php` | REST routes | 4 |
| 36 | `includes/Modules/Timeline/TimelineAjaxController.php` | AJAX handlers | 4 |

### 5.4 Problems Module (5 files)

| # | File Path | Role | Phase |
|---|-----------|------|-------|
| 37 | `includes/Modules/Problems/ProblemEntity.php` | Symptom → Problem → Root Cause → Consequence | 5 |
| 38 | `includes/Modules/Problems/ProblemRepository.php` | Persistence | 5 |
| 39 | `includes/Modules/Problems/ProblemService.php` | Business logic | 5 |
| 40 | `includes/Modules/Problems/ProblemRestController.php` | REST routes | 5 |
| 41 | `includes/Modules/Problems/ProblemAjaxController.php` | AJAX handlers | 5 |

### 5.5 Stakeholders Module (5 files)

| # | File Path | Role | Phase |
|---|-----------|------|-------|
| 42 | `includes/Modules/Stakeholders/StakeholderEntity.php` | Interest/Influence/Impact/Position | 5 |
| 43 | `includes/Modules/Stakeholders/StakeholderRepository.php` | Persistence | 5 |
| 44 | `includes/Modules/Stakeholders/StakeholderService.php` | Business logic | 5 |
| 45 | `includes/Modules/Stakeholders/StakeholderRestController.php` | REST routes | 5 |
| 46 | `includes/Modules/Stakeholders/StakeholderAjaxController.php` | AJAX handlers | 5 |

### 5.6 Sources Module (5 files)

| # | File Path | Role | Phase |
|---|-----------|------|-------|
| 47 | `includes/Modules/Sources/SourceEntity.php` | Reusable source library record | 6 |
| 48 | `includes/Modules/Sources/SourceRepository.php` | Persistence | 6 |
| 49 | `includes/Modules/Sources/SourceService.php` | Business logic | 6 |
| 50 | `includes/Modules/Sources/SourceRestController.php` | REST routes | 6 |
| 51 | `includes/Modules/Sources/SourceAjaxController.php` | AJAX handlers | 6 |

### 5.7 Evidence Module (5 files)

| # | File Path | Role | Phase |
|---|-----------|------|-------|
| 52 | `includes/Modules/Evidence/EvidenceEntity.php` | Claim, source, reliability, citation, finding | 6 |
| 53 | `includes/Modules/Evidence/EvidenceRepository.php` | Persistence | 6 |
| 54 | `includes/Modules/Evidence/EvidenceService.php` | Business logic | 6 |
| 55 | `includes/Modules/Evidence/EvidenceRestController.php` | REST routes | 6 |
| 56 | `includes/Modules/Evidence/EvidenceAjaxController.php` | AJAX handlers | 6 |

### 5.8 Claims Module (5 files)

| # | File Path | Role | Phase |
|---|-----------|------|-------|
| 57 | `includes/Modules/Claims/ClaimEntity.php` | Claim-to-evidence relationship | 6 |
| 58 | `includes/Modules/Claims/ClaimRepository.php` | Persistence | 6 |
| 59 | `includes/Modules/Claims/ClaimService.php` | Business logic | 6 |
| 60 | `includes/Modules/Claims/ClaimRestController.php` | REST routes | 6 |
| 61 | `includes/Modules/Claims/ClaimAjaxController.php` | AJAX handlers | 6 |

### 5.9 Data Module (5 files)

| # | File Path | Role | Phase |
|---|-----------|------|-------|
| 62 | `includes/Modules/Data/DataEntity.php` | DataTable, Measurement, KPI | 7 |
| 63 | `includes/Modules/Data/DataTableRepository.php` | Persistence | 7 |
| 64 | `includes/Modules/Data/DataService.php` | Business logic | 7 |
| 65 | `includes/Modules/Data/DataRestController.php` | REST routes | 7 |
| 66 | `includes/Modules/Data/DataAjaxController.php` | AJAX handlers | 7 |

### 5.10 Extraction Module — Base (5 files)

| # | File Path | Role | Phase |
|---|-----------|------|-------|
| 67 | `includes/Modules/Extraction/ExtractionEntity.php` | ExtractionJob, ExtractedRecord | 7 |
| 68 | `includes/Modules/Extraction/ExtractionRepository.php` | Persists extracted records + provenance | 7 |
| 69 | `includes/Modules/Extraction/ExtractionService.php` | Pipeline: EXTRACT → NORMALIZE → VALIDATE → PERSIST → REUSE → CLEANUP | 7 |
| 70 | `includes/Modules/Extraction/ExtractionRestController.php` | REST routes | 7 |
| 71 | `includes/Modules/Extraction/ExtractionAjaxController.php` | AJAX handlers | 7 |

### 5.11 Extraction Module — Ingestion (2 files)

| # | File Path | Role | Phase |
|---|-----------|------|-------|
| 72 | `includes/Modules/Extraction/Ingestion/TemporaryFileHandler.php` | Temp file storage only | 7 |
| 73 | `includes/Modules/Extraction/Ingestion/FileValidator.php` | Format/size/integrity validation | 7 |

### 5.12 Extraction Module — Parsers (4 files)

| # | File Path | Role | Phase |
|---|-----------|------|-------|
| 74 | `includes/Modules/Extraction/Parsers/ParserInterface.php` | Common parser contract | 7 |
| 75 | `includes/Modules/Extraction/Parsers/PdfParser.php` | PDF extraction | 7 |
| 76 | `includes/Modules/Extraction/Parsers/XlsxParser.php` | XLSX/XLS extraction | 7 |
| 77 | `includes/Modules/Extraction/Parsers/CsvParser.php` | CSV extraction | 7 |

### 5.13 Extraction Module — Normalization (3 files)

| # | File Path | Role | Phase |
|---|-----------|------|-------|
| 78 | `includes/Modules/Extraction/Normalization/NumericNormalizer.php` | Currency/unit/number normalization | 7 |
| 79 | `includes/Modules/Extraction/Normalization/DateNormalizer.php` | Date/period normalization | 7 |
| 80 | `includes/Modules/Extraction/Normalization/MetricClassifier.php` | Metric naming/classification | 7 |

### 5.14 Extraction Module — Provenance, Lifecycle, Cleanup (3 files)

| # | File Path | Role | Phase |
|---|-----------|------|-------|
| 81 | `includes/Modules/Extraction/Provenance/ProvenanceRecorder.php` | Source metadata + reference + extraction method + validation status | 7 |
| 82 | `includes/Modules/Extraction/Lifecycle/ExtractionStatus.php` | EXTRACTED/REVIEWED/VERIFIED/REJECTED/CORRECTED enum | 7 |
| 83 | `includes/Modules/Extraction/Cleanup/TemporaryFileCleanup.php` | Temp file cleanup — success AND failure | 7 |

### 5.15 Frameworks Module (5 files)

| # | File Path | Role | Phase |
|---|-----------|------|-------|
| 84 | `includes/Modules/Frameworks/FrameworkEntity.php` | Framework analysis record | 8 |
| 85 | `includes/Modules/Frameworks/FrameworkRepository.php` | Persistence | 8 |
| 86 | `includes/Modules/Frameworks/FrameworkService.php` | Business logic | 8 |
| 87 | `includes/Modules/Frameworks/FrameworkRestController.php` | REST routes | 8 |
| 88 | `includes/Modules/Frameworks/FrameworkAjaxController.php` | AJAX handlers | 8 |

### 5.16 RootCause Module (5 files)

| # | File Path | Role | Phase |
|---|-----------|------|-------|
| 89 | `includes/Modules/RootCause/RootCauseEntity.php` | 5 Whys, Fishbone, Cause-effect | 9 |
| 90 | `includes/Modules/RootCause/RootCauseRepository.php` | Persistence | 9 |
| 91 | `includes/Modules/RootCause/RootCauseService.php` | Business logic | 9 |
| 92 | `includes/Modules/RootCause/RootCauseRestController.php` | REST routes | 9 |
| 93 | `includes/Modules/RootCause/RootCauseAjaxController.php` | AJAX handlers | 9 |

### 5.17 Alternatives Module (5 files)

| # | File Path | Role | Phase |
|---|-----------|------|-------|
| 94 | `includes/Modules/Alternatives/AlternativeEntity.php` | Candidate solutions with criteria/ratings | 10 |
| 95 | `includes/Modules/Alternatives/AlternativeRepository.php` | Persistence | 10 |
| 96 | `includes/Modules/Alternatives/AlternativeService.php` | Business logic | 10 |
| 97 | `includes/Modules/Alternatives/AlternativeRestController.php` | REST routes | 10 |
| 98 | `includes/Modules/Alternatives/AlternativeAjaxController.php` | AJAX handlers | 10 |

### 5.18 Decisions Module (5 files)

| # | File Path | Role | Phase |
|---|-----------|------|-------|
| 99 | `includes/Modules/Decisions/DecisionEntity.php` | Decision matrix, final decision, rationale | 10 |
| 100 | `includes/Modules/Decisions/DecisionRepository.php` | Persistence | 10 |
| 101 | `includes/Modules/Decisions/DecisionService.php` | Business logic | 10 |
| 102 | `includes/Modules/Decisions/DecisionRestController.php` | REST routes | 10 |
| 103 | `includes/Modules/Decisions/DecisionAjaxController.php` | AJAX handlers | 10 |

### 5.19 Risks Module (5 files)

| # | File Path | Role | Phase |
|---|-----------|------|-------|
| 104 | `includes/Modules/Risks/RiskEntity.php` | Probability, impact, severity, mitigation | 10 |
| 105 | `includes/Modules/Risks/RiskRepository.php` | Persistence | 10 |
| 106 | `includes/Modules/Risks/RiskService.php` | Business logic | 10 |
| 107 | `includes/Modules/Risks/RiskRestController.php` | REST routes | 10 |
| 108 | `includes/Modules/Risks/RiskAjaxController.php` | AJAX handlers | 10 |

### 5.20 Recommendations Module (5 files)

| # | File Path | Role | Phase |
|---|-----------|------|-------|
| 109 | `includes/Modules/Recommendations/RecommendationEntity.php` | Finding → Implication → Recommendation → Action → KPI | 11 |
| 110 | `includes/Modules/Recommendations/RecommendationRepository.php` | Persistence | 11 |
| 111 | `includes/Modules/Recommendations/RecommendationService.php` | Business logic | 11 |
| 112 | `includes/Modules/Recommendations/RecommendationRestController.php` | REST routes | 11 |
| 113 | `includes/Modules/Recommendations/RecommendationAjaxController.php` | AJAX handlers | 11 |

### 5.21 Implementation Module (5 files)

| # | File Path | Role | Phase |
|---|-----------|------|-------|
| 114 | `includes/Modules/Implementation/ImplementationEntity.php` | Action, owner, start/end, status, KPI | 11 |
| 115 | `includes/Modules/Implementation/ImplementationRepository.php` | Persistence | 11 |
| 116 | `includes/Modules/Implementation/ImplementationService.php` | Business logic | 11 |
| 117 | `includes/Modules/Implementation/ImplementationRestController.php` | REST routes | 11 |
| 118 | `includes/Modules/Implementation/ImplementationAjaxController.php` | AJAX handlers | 11 |

### 5.22 Outcomes Module (5 files)

| # | File Path | Role | Phase |
|---|-----------|------|-------|
| 119 | `includes/Modules/Outcomes/OutcomeEntity.php` | Expected vs. actual per metric | 12 |
| 120 | `includes/Modules/Outcomes/OutcomeRepository.php` | Persistence | 12 |
| 121 | `includes/Modules/Outcomes/OutcomeService.php` | Business logic | 12 |
| 122 | `includes/Modules/Outcomes/OutcomeRestController.php` | REST routes | 12 |
| 123 | `includes/Modules/Outcomes/OutcomeAjaxController.php` | AJAX handlers | 12 |

### 5.23 Findings Module (5 files)

| # | File Path | Role | Phase |
|---|-----------|------|-------|
| 124 | `includes/Modules/Findings/FindingEntity.php` | Statement, evidence, analysis, confidence, implication | 12 |
| 125 | `includes/Modules/Findings/FindingRepository.php` | Persistence | 12 |
| 126 | `includes/Modules/Findings/FindingService.php` | Business logic | 12 |
| 127 | `includes/Modules/Findings/FindingRestController.php` | REST routes | 12 |
| 128 | `includes/Modules/Findings/FindingAjaxController.php` | AJAX handlers | 12 |

### 5.24 Lessons Module (5 files)

| # | File Path | Role | Phase |
|---|-----------|------|-------|
| 129 | `includes/Modules/Lessons/LessonEntity.php` | Category, generalizable, related finding | 12 |
| 130 | `includes/Modules/Lessons/LessonRepository.php` | Persistence | 12 |
| 131 | `includes/Modules/Lessons/LessonService.php` | Business logic | 12 |
| 132 | `includes/Modules/Lessons/LessonRestController.php` | REST routes | 12 |
| 133 | `includes/Modules/Lessons/LessonAjaxController.php` | AJAX handlers | 12 |

### 5.25 Publishing Module (5 files)

| # | File Path | Role | Phase |
|---|-----------|------|-------|
| 134 | `includes/Modules/Publishing/PublicationEntity.php` | PublicationSnapshot, PublicationRecord | 13 |
| 135 | `includes/Modules/Publishing/PublicationRepository.php` | Persistence | 13 |
| 136 | `includes/Modules/Publishing/PublicationService.php` | Creates native WP draft | 13 |
| 137 | `includes/Modules/Publishing/PublicationRestController.php` | REST routes | 13 |
| 138 | `includes/Modules/Publishing/PublicationAjaxController.php` | AJAX handlers | 13 |

---

## 6. ADMIN — SCREEN CONTROLLERS (16 files)

| # | File Path | Role | Phase |
|---|-----------|------|-------|
| 139 | `includes/Admin/Dashboard/DashboardController.php` | Dashboard screen (UI §2) | 14 |
| 140 | `includes/Admin/Cases/CasesController.php` | Cases list screen (UI §3.1) | 3 |
| 141 | `includes/Admin/Cases/CaseWorkspaceController.php` | 12-tab Case Workspace (UI §4) | 3 |
| 142 | `includes/Admin/Sources/SourcesController.php` | Sources Library screen (UI §17) | 6 |
| 143 | `includes/Admin/Evidence/EvidenceController.php` | Evidence admin helpers | 6 |
| 144 | `includes/Admin/Stakeholders/StakeholdersController.php` | Stakeholder admin helpers | 5 |
| 145 | `includes/Admin/Analysis/AnalysisController.php` | Analysis tab UI (UI §11) | 8 |
| 146 | `includes/Admin/Frameworks/FrameworksController.php` | Framework picker UI | 8 |
| 147 | `includes/Admin/Decisions/DecisionsController.php` | Decision tab UI (UI §12) | 10 |
| 148 | `includes/Admin/Risks/RisksController.php` | Risk matrix UI | 10 |
| 149 | `includes/Admin/Recommendations/RecommendationsController.php` | Recommendations tab UI (UI §13) | 11 |
| 150 | `includes/Admin/Findings/FindingsController.php` | Findings UI | 12 |
| 151 | `includes/Admin/Publishing/PublishingController.php` | Publication tab UI (UI §16) | 13 |
| 152 | `includes/Admin/License/LicenseAdminController.php` | License screen (UI §20) | 2 |
| 153 | `includes/Admin/Settings/SettingsController.php` | Settings screen — 11 tabs (UI §19) | 14 |
| 154 | `includes/Admin/Tools/ToolsController.php` | Tools screen (UI §18) | 14 |

---

## 7. GUTENBERG — INTEGRATION (3 files)

| # | File Path | Role | Phase |
|---|-----------|------|-------|
| 155 | `includes/Gutenberg/Integration/PublishingServiceBridge.php` | Creates native WP draft, pre-populates blocks | 13 |
| 156 | `includes/Gutenberg/Integration/BlockRegistrar.php` | Registers all 21 blocks | 13 |
| 157 | `includes/Gutenberg/Integration/BlueprintLoader.php` | Blueprint-to-block-sequence mapping | 13 |

---

## 8. GUTENBERG — BLOCK RENDER FILES (21 files)

Each block folder also contains `block.json`, `index.js`, `edit.js`, `save.js`, `style.css`, `editor.css` (not PHP — not counted here).

| # | File Path | Block Name | Phase |
|---|-----------|------------|-------|
| 158 | `includes/Gutenberg/Blocks/CaseSummary/render.php` | SI Case Summary | 13 |
| 159 | `includes/Gutenberg/Blocks/CaseContext/render.php` | SI Case Context | 13 |
| 160 | `includes/Gutenberg/Blocks/Problem/render.php` | SI Problem | 13 |
| 161 | `includes/Gutenberg/Blocks/Stakeholders/render.php` | SI Stakeholders | 13 |
| 162 | `includes/Gutenberg/Blocks/Timeline/render.php` | SI Timeline | 13 |
| 163 | `includes/Gutenberg/Blocks/Evidence/render.php` | SI Evidence | 13 |
| 164 | `includes/Gutenberg/Blocks/CaseData/render.php` | SI Case Data | 13 |
| 165 | `includes/Gutenberg/Blocks/RootCause/render.php` | SI Root Cause | 13 |
| 166 | `includes/Gutenberg/Blocks/SWOT/render.php` | SI SWOT | 13 |
| 167 | `includes/Gutenberg/Blocks/PESTLE/render.php` | SI PESTLE | 13 |
| 168 | `includes/Gutenberg/Blocks/FiveForces/render.php` | SI Five Forces | 13 |
| 169 | `includes/Gutenberg/Blocks/SevenS/render.php` | SI 7S | 13 |
| 170 | `includes/Gutenberg/Blocks/Fishbone/render.php` | SI Fishbone | 13 |
| 171 | `includes/Gutenberg/Blocks/FiveWhys/render.php` | SI 5 Whys | 13 |
| 172 | `includes/Gutenberg/Blocks/DecisionMatrix/render.php` | SI Decision Matrix | 13 |
| 173 | `includes/Gutenberg/Blocks/RiskMatrix/render.php` | SI Risk Matrix | 13 |
| 174 | `includes/Gutenberg/Blocks/Recommendation/render.php` | SI Recommendation | 13 |
| 175 | `includes/Gutenberg/Blocks/Implementation/render.php` | SI Implementation | 13 |
| 176 | `includes/Gutenberg/Blocks/Outcome/render.php` | SI Outcome | 13 |
| 177 | `includes/Gutenberg/Blocks/Lessons/render.php` | SI Lessons | 13 |
| 178 | `includes/Gutenberg/Blocks/Source/render.php` | SI Case Source | 13 |

---

## COMPLETE FILE COUNT SUMMARY

| Category | File Count |
|----------|-----------|
| 1. Root Files | 3 |
| 2. SI Framework / Core | 6 |
| 3. SI Framework / Engine | 4 |
| 4. SI Framework / Premium (incl. SDK) | 8 |
| 5. Modules — Base (21 modules × 5 files each) | 105 |
| 5. Modules — Extraction Additional (Ingestion + Parsers + Normalization + Provenance + Lifecycle + Cleanup) | 12 |
| 6. Admin Controllers | 16 |
| 7. Gutenberg Integration | 3 |
| 8. Gutenberg Block render.php | 21 |
| **GRAND TOTAL** | **178** |

---

## LOAD ORDER DEPENDENCY GRAPH
