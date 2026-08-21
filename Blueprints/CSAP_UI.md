# CSAP UI — Admin Menu & Case Workspace Specification

Companion to `SI_CSAP_Production_blueprint.md`. Defines every admin screen, its layout, and the components on it.

**Governing rule:** Evidence, Analysis, Risks, Recommendations, Findings, and Decisions do **not** get their own top-level WordPress admin menus — they live inside the contextual Case Workspace (§5). This keeps the plugin to a small, learnable top-level menu instead of 20 disconnected pages.

---

## 1. Top-Level Admin Menu

| Order | Menu | Icon | Purpose | UI Type |
|---|---|---|---|---|
| 1 | **SI Case Analysis** | 🧠 | Plugin dashboard / entry point | Dashboard |
| 2 | **Cases** | 📂 | Manage all case studies | List + Workspace |
| 3 | **Sources Library** | 📚 | Reusable source & citation management | List + Detail |
| 4 | **Tools** | 🛠️ | Import, extraction, maintenance, utilities | Tools |
| 5 | **Settings** | ⚙️ | Plugin configuration | Settings Tabs |
| 6 | **License** | 🔑 | License activation and status | License Screen |

```
🧠 SI Case Analysis
│
├── 📊 Dashboard
├── 📂 Cases
├── 📚 Sources Library
├── 🛠️ Tools
├── ⚙️ Settings
└── 🔑 License
```

## 2. Dashboard

**Menu:** SI Case Analysis → Dashboard

| Section | Position | Contents |
|---|---|---|
| Page Header | Top | "SI Case Study Analysis Pro" + Quick Actions |
| Quick Actions | Top Right | `+ New Case`, `Import Data`, `Add Source` |
| Case Statistics | Row 1 | Total Cases, Active Cases, Archived Cases |
| Evidence Statistics | Row 1 | Evidence Records, Supported Claims, Source Count |
| Publication Statistics | Row 2 | Drafts, Published Cases, Ready for Publication |
| Recent Cases | Main Left | Recently edited cases |
| Case Progress | Main Right | Case completion/readiness status |
| Evidence Health | Lower Left | High / Medium / Low reliability breakdown |
| Publication Readiness | Lower Right | Cases ready or needing attention |
| Recent Activity | Bottom | Recent changes and actions |

**Wireframe:**

```
┌─────────────────────────────────────────────────────────────┐
│  SI Case Study Analysis Pro     [+ New Case] [Import] [Add Source] │
├─────────────────────────────────────────────────────────────┤
│  Cases: 24 Active | Evidence: 182 Records | Findings: 47 | Drafts: 8 │
├─────────────────────────────────────────────────────────────┤
│  Main Content                     │  Sidebar                 │
│  - Recent Cases                   │  - Publication Readiness │
│  - Case Progress                  │  - Evidence Health       │
│  - Recent Activity                │  - Quick Links           │
└─────────────────────────────────────────────────────────────┘
```

The Evidence Vault reliability breakdown (§9) and quality/readiness scores feed this dashboard directly.

## 3. Cases

**Menu:** SI Case Analysis → Cases

### 3.1 Cases List

| UI Area | Component | Description |
|---|---|---|
| Header | "Cases" | Page title |
| Primary Button | `+ Create New Case` | Starts case creation |
| Search | Search Cases | Search title, type, status |
| Filter | Status | Active / Draft / Archived |
| Filter | Case Type | Business / HR / Finance / etc. |
| Filter | Owner | My Cases / All Cases |
| Bulk Actions | Actions | Archive / Delete where authorized |
| Table | Cases List | Main case management table |

**Table columns:** Case, Type, Status, Owner, Evidence, Findings, Readiness, Last Updated, Actions.

Example row: `ABC Corporation Crisis | Business | Active | Admin | 24 | 8 | 82% | Today | Open`

### 3.2 New Case Creation

**Menu:** Cases → Create New Case

| Section | Field/Component |
|---|---|
| Case Information | Case Title |
| Classification | Case Type |
| Classification | Category |
| Classification | Industry / Sector |
| Classification | Organization / Subject |
| Status | Draft / Active |
| Ownership | Case Owner |
| Description | Initial Case Summary |
| Actions | `Create Case` / `Cancel` |

```
Create New Case → Basic Information → Create Case → Open Case Workspace
```

> Case creation never automatically creates a WordPress post — the Case and the WordPress publication stay separate until the user explicitly publishes (§16).

## 4. Case Workspace — The Main Plugin Screen

This is the most important screen in the entire plugin.

**Top header:**

| Field | Contents |
|---|---|
| Breadcrumb | SI Case Analysis → Cases → Current Case |
| Case Title | Current case name |
| Status | Draft / Active / Archived |
| Quality | Quality Score |
| Readiness | Publication Readiness |
| Actions | Save / Create Draft / More Actions |

**Workspace tabs (12, in order):**

| # | Tab | Primary Purpose |
|---|---|---|
| 1 | Overview | Complete case summary |
| 2 | Context | Background and case environment |
| 3 | Problem | Problem definition |
| 4 | Stakeholders | Stakeholder analysis |
| 5 | Evidence | Evidence and claims |
| 6 | Data | Tables, datasets, KPIs |
| 7 | Analysis | Analytical frameworks |
| 8 | Decision | Alternatives and decisions |
| 9 | Recommendations | Findings and recommendations |
| 10 | Implementation | Action plans and timelines |
| 11 | Outcome & Lessons | Results and learning |
| 12 | Publication | Create publication draft |

**Visual layout:**

```
┌───────────────────────────────────────────────────────────────────┐
│ SI Case Analysis > Cases > ABC Corporation Crisis                 │
│ ABC CORPORATION CRISIS   Active   Quality: 82%   Ready: 78%       │
│                                                   [Create Draft]   │
├───────────────────────────────────────────────────────────────────┤
│ Overview│Context│Problem│Stakeholders│Evidence│Data│Analysis│      │
│ Decision│Recommendations│Implementation│Outcome│Lessons│Publication│
├───────────────────────────────────────────────────────────────────┤
│                     ACTIVE WORKSPACE CONTENT                      │
└───────────────────────────────────────────────────────────────────┘
```

## 5. Workspace Tab: Overview

| Section | Contents |
|---|---|
| Case Summary | Short description |
| Case Classification | Type, category, industry |
| Problem Status | Defined / Missing |
| Evidence Status | Evidence count and coverage |
| Analysis Status | Completed frameworks |
| Findings | Finding count |
| Recommendations | Recommendation count |
| Implementation | Action plan status |
| Publication | Draft / Published / Not Created |
| Quality Control | Checklist |
| Recent Activity | Recent modifications |

Status cards: `Context ✓ Complete | Problem ✓ Defined | Evidence: 24 Records | Analysis: 4 Complete`

## 6. Workspace Tab: Context

| Section | UI Component |
|---|---|
| Case Background | Rich text editor |
| Organization | Structured field |
| Industry | Select / taxonomy |
| Location / Market | Structured field |
| Time Period | Date / period fields |
| Key Events | Timeline items |
| Initial Conditions | Structured notes |
| Limitations | Limitations field |

Bottom actions: `Save Context` | `Save & Continue to Problem`

## 7. Workspace Tab: Problem

| Section | Purpose |
|---|---|
| Primary Problem | Main problem statement |
| Problem Description | Detailed explanation |
| Problem Category | Strategic / Financial / HR / etc. |
| Symptoms | Observable symptoms |
| Impact | Business/organizational impact |
| Constraints | Limitations |
| Related Evidence | Linked evidence |
| Related Stakeholders | Linked stakeholders |

Layout: a rich structured editor for the Problem Statement, with two side-by-side panels below it — Supporting Evidence (evidence cards) and Related Stakeholders (stakeholder cards).

## 8. Workspace Tab: Stakeholders

| Stakeholder | Interest | Influence | Impact | Position | Actions |
|---|---|---|---|---|---|
| Employees | High | Medium | High | Supportive | Edit |
| Management | High | High | High | Mixed | Edit |
| Customers | High | Medium | High | Concerned | Edit |

| Section | UI |
|---|---|
| Add Stakeholder | Button + modal/drawer |
| Stakeholder List | Table |
| Influence/Interest Matrix | Visual matrix |
| Related Evidence | Linked records |
| Related Risks | Linked risks |

## 9. Workspace Tab: Evidence (Case Evidence Vault)

Header actions: `+ Add Evidence` · `Link Existing Source` · `Import/Extract`

| Claim / Evidence | Source | Type | Reliability | Finding Links | Status | Actions |
|---|---|---|---|---|---|---|
| Revenue declined | Annual Report | Financial | High | 2 | Verified | View |
| Morale decreased | Survey | Survey | Medium | 1 | Review | View |

**Evidence detail drawer:**

| Section | Content |
|---|---|
| Evidence Statement | Main evidence |
| Claim | Related claim(s) |
| Source | Linked source |
| Citation | Citation/reference |
| Reliability | High / Medium / Low |
| Page / Reference | Page number/location |
| Notes | Analyst notes |
| Findings | Related findings |

## 10. Workspace Tab: Data & KPI

Sub-navigation: **Data Tables** · **Datasets** · **Measurements** · **KPIs**

**Data Tables:** Table Name, Records, Source, Last Updated, Actions.
**KPIs:** KPI, Current, Target, Direction, Status — e.g. `Employee Turnover | 18% | 12% | ↓ | Needs Improvement`.

This stays a structured, case-specific KPI list — never a generic shortcode-driven KPI vault.

## 11. Workspace Tab: Analysis

| Framework | Status | Last Updated | Action |
|---|---|---|---|
| SWOT | Complete | Today | Open |
| PESTLE | In Progress | Yesterday | Continue |
| Five Forces | Not Started | — | Start |
| 7S | Not Started | — | Start |
| Fishbone | Complete | 2 days ago | Open |
| Five Whys | Complete | 2 days ago | Open |

Framework picker grid:

```
[ SWOT ]        [ PESTLE ]
[ Five Forces ] [ 7S ]
[ Fishbone ]    [ Five Whys ]
[ Root Cause ]  [ Custom Analysis ]
```

Each framework tile opens its own modular editor.

## 12. Workspace Tab: Decision

Sub-tabs: **Alternatives** · **Evaluation** · **Decision Matrix** · **Final Decision** · **Decision Rationale**

| Alternative | Cost | Impact | Risk | Feasibility | Score |
|---|---|---|---|---|---|
| Option A | 7 | 9 | 6 | 8 | 7.5 |
| Option B | 8 | 7 | 8 | 9 | 8.0 |
| Option C | 6 | 8 | 7 | 7 | 7.0 |

## 13. Workspace Tab: Recommendations

Structured around the core chain: `Finding → Implication → Recommendation → Action → KPI`.

| Recommendation | Based on Finding | Priority | Owner | Status | Actions |
|---|---|---|---|---|---|
| Improve Retention Strategy | Finding #4 | High | HR Manager | Draft | Open |
| Revise Pricing Model | Finding #7 | Medium | CFO | Approved | Open |

Detail view walks the chain top to bottom: Finding → Implication → Recommendation → Rationale → Implementation Actions → KPIs.

## 14. Workspace Tab: Implementation

| Action | Owner | Start | End | Status | KPI |
|---|---|---|---|---|---|
| Staff Survey | HR | Aug 20 | Sep 01 | Planned | Satisfaction |
| Policy Review | Management | Sep 02 | Sep 15 | Planned | Turnover |
| Training Program | HR | Sep 20 | Oct 20 | Draft | Retention |

Supporting components: Timeline, Action Plan, Owners, Resources, KPIs, Dependencies, Risks.

## 15. Workspace Tab: Outcome & Lessons

**Outcome:**

| Outcome | Expected | Actual | Status |
|---|---|---|---|
| Employee Turnover | 12% | 14% | Improving |
| Revenue | $120M | $115M | Below Target |

**Lessons:**

| Lesson | Category | Generalizable | Related Finding |
|---|---|---|---|
| Communication was insufficient | Leadership | Yes | Finding #4 |
| Early intervention reduced risk | Strategic | Yes | Finding #7 |

## 16. Workspace Tab: Publication

A controlled publication workflow — not automatic synchronization.

| Section | UI |
|---|---|
| Publication Status | Not Created / Draft / Published |
| Publication Blueprint | Select optional blueprint |
| Content Preview | Selected sections |
| Case Version | Source case version |
| Publication Snapshot | Snapshot information |
| WordPress Draft | Linked post |
| Last Published | Date |
| Actions | Create Draft / Open in Gutenberg |

**Publication flow:**

```
CASE → Select Publication Blueprint → Select Case Sections →
Create Publication Snapshot → Create Native WordPress Draft →
Open Gutenberg → User Reviews / Edits → Publish
```

Publishing is always explicitly initiated by the user — unrelated WordPress posts are never touched.

## 17. Sources Library

**Menu:** SI Case Analysis → Sources Library

Sources are reusable, not trapped inside a single case.

| Source | Type | Author / Publisher | Year | Reliability | Used In | Actions |
|---|---|---|---|---|---|---|
| Annual Report 2025 | Report | ABC Corp | 2025 | High | 3 Cases | Open |
| Industry Survey | Survey | Research Group | 2024 | Medium | 5 Cases | Open |

**Source detail tabs:** Overview (metadata) · Citation (citation info) · Evidence (linked evidence) · Cases (cases using this source) · References (page/reference details).

## 18. Tools

**Menu:** SI Case Analysis → Tools

| Tool | Purpose | Priority |
|---|---|---|
| Import Case Data | Import structured case information | Core |
| CSV Import | Import data tables | Core |
| XLSX Import | Import spreadsheet data | Core |
| Document Extraction | Extract structured information from PDFs/reports | Later |
| Data Cleanup | Remove temporary/orphaned data | Admin |
| Recalculate Quality | Recalculate quality/readiness scores | Admin |
| System Health | Check database/system status | Admin |
| Export Case | Export structured case data | Recommended |

Document Extraction uses temporary processing and cleanup rather than permanently retaining documents by default (see the Data Extraction Architecture in the production blueprint, §19).

## 19. Settings

**Menu:** SI Case Analysis → Settings

| Tab | Settings |
|---|---|
| General | Plugin defaults |
| Cases | Default case behavior |
| Evidence | Reliability settings |
| Data | Import/data configuration |
| Analysis | Framework settings |
| Publishing | Publication defaults |
| Gutenberg | Block integration |
| AI | AI provider/settings |
| SEO | Yoast/Rank Math integration |
| Security | Access/security rules |
| Advanced | Maintenance/developer options |

The SEO tab stays an adapter/integration layer — never a separate SEO engine.

## 20. License

**Menu:** SI Case Analysis → License

| UI Component | Purpose |
|---|---|
| License Key | Enter/update license |
| Status | Active/Inactive/Expired |
| Licensed Email | License identity |
| Verify | Verify license |
| Deactivate | Deactivate license |
| License Details | Product/license information |
| Premium Features | Entitlement status |

```
Plugin → LicenseController → Product-specific SDK → LicenseState → FeatureManager → Premium Features
```

Modules never access SDK internals directly; no unnecessary licensing controls are exposed to the end user.

## 21. Final Admin UI Hierarchy

```
🧠 SI CASE ANALYSIS
│
├── 📊 Dashboard
│
├── 📂 Cases
│   ├── All Cases
│   ├── Create New Case
│   ├── Active Cases
│   └── Archived Cases
│
│   └── CASE WORKSPACE
│       ├── Overview
│       ├── Context
│       ├── Problem
│       ├── Stakeholders
│       ├── Evidence
│       ├── Data
│       ├── Analysis
│       ├── Decision
│       ├── Recommendations
│       ├── Implementation
│       ├── Outcome & Lessons
│       └── Publication
│
├── 📚 Sources Library
│   ├── All Sources
│   ├── Add Source
│   └── Source Categories
│
├── 🛠️ Tools
│   ├── Import
│   ├── Document Extraction
│   ├── Export
│   ├── Data Maintenance
│   └── System Health
│
├── ⚙️ Settings
│   ├── General
│   ├── Evidence
│   ├── Data
│   ├── Analysis
│   ├── Publishing
│   ├── Gutenberg
│   ├── AI
│   ├── SEO
│   ├── Security
│   └── Advanced
│
└── 🔑 License
    ├── Status
    ├── Activate
    ├── Verify
    └── Deactivate
```
