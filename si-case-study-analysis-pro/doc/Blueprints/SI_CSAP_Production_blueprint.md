# SI Case Study Analysis Pro — Production Blueprint

**Architecture:** SI Framework v3.0
**Publishing Layer:** Gutenberg-first (Native WordPress Posts)
**Content Model:** Case Intelligence Engine + Gutenberg Publishing Layer
**Licensing:** Product-specific SDK via `LicenseController`
**Status:** Consolidated production architecture (merges Product Blueprint, UI Spec, Data Extraction Architecture, and Licensing & Security Guidelines into one authoritative reference)

This document is the single source of truth for the product. `CSAP_php_directory.md`, `CSAP_UI.md`, and `CSAP_AI_Coding.md` are derived from it and must not contradict it. If a conflict appears, this file wins.

---

## 1. Product Vision

SI Case Study Analysis Pro is a **structured case-study intelligence and publishing system** for WordPress. It turns real-world business, organizational, HR, leadership, financial, policy, operational, strategic, and academic cases into structured, evidence-based WordPress publications.

Core pipeline:

```
Case → Context → Problem → Evidence → Analysis → Alternatives →
Recommendation → Implementation → Outcome → Lessons → Publication
```

The plugin is **not** a template builder. It is a:

> **Case Analysis Workspace + Evidence Management System + Gutenberg Publishing System.**

## 2. Product Philosophy

```
CASE → CASE CONTEXT → PROBLEM → STAKEHOLDERS → EVIDENCE → DATA →
ROOT CAUSE → ANALYSIS → ALTERNATIVES → DECISION → RECOMMENDATION →
IMPLEMENTATION → OUTCOME → LESSONS → GUTENBERG → NATIVE WORDPRESS POST
```

## 3. What This Plugin Is NOT

The production build must **never** become:

- a Meta Box or ACF template builder
- a drag-and-drop article generator
- a shortcode-only system
- an HTML/CSS sandbox
- a custom article editor or a replacement for Gutenberg
- a custom publishing system
- a generic AI chatbot
- a collection of static article fields

The user always writes and arranges the final publication in Gutenberg.

## 4. Primary Users

| User | Primary Use |
|---|---|
| Business Analyst | Business cases |
| Management Consultant | Consulting cases |
| Researcher | Academic case studies |
| MBA Student | Case analysis |
| HR Professional | HR cases |
| Leadership Professional | Leadership cases |
| Financial Analyst | Financial cases |
| Economist | Economic/policy cases |
| Entrepreneur | Business decisions |
| Journalist | Investigative cases |
| Blogger | Professional case publications |
| Teacher | Teaching cases |
| Corporate Manager | Organizational decisions |

## 5. Case Study Domains

- **Business:** strategy, market entry, competition, growth, restructuring, crisis management
- **Management:** organizational/operational problems, decision-making, process improvement, resource allocation
- **HRM:** employee relations, recruitment, retention, performance, compensation, culture, workforce transformation
- **Leadership:** failure, transition, organizational change, executive decision-making, conflict, crisis leadership
- **Finance:** distress, investment decisions, credit risk, profitability, liquidity, capital allocation
- **Policy:** public policy, regulation, institutional problems, development programs

## 6. Case Classification

| Field | Example |
|---|---|
| Case Type | Business |
| Industry | Banking |
| Geography | Bangladesh |
| Organization | Bank |
| Period | 2020–2025 |
| Case Status | Completed |
| Complexity | Advanced |
| Confidentiality | Public |
| Analysis Type | Strategic |

## 7. Case Entity

The **Case** is the central analytical object:

```
Case
├── Identity
├── Context
├── Timeline
├── Problem
├── Stakeholders
├── Evidence
├── Data
├── Analysis
├── Root Causes
├── Alternatives
├── Decisions
├── Recommendations
├── Implementation
├── Outcomes
├── Lessons
├── Sources
└── Publication
```

### 7.1 Case vs. WordPress Post

The Case and the WordPress Post are **permanently separate concepts**:

```
Case → Analysis → Findings → Publication Blueprint → Gutenberg Draft → WordPress Post
```

- A case record must never be treated as a WordPress post.
- A WordPress post must never be used as the case database.
- Case creation must **not** automatically create a WordPress post.

### 7.2 Case Identity fields

Case ID, title, short title, case type, industry, organization, geography, period, analyst, status, confidentiality, tags, notes.

## 8. Case Context

Answers "what is happening?": organization background, industry background, market environment, economic environment, political/regulatory environment, organizational structure, historical context, relevant events. Structured entry is permitted without forcing the final article into the same order.

## 9. Timeline

```
2019 → Market Change
2020 → Strategic Decision
2021 → Problem Emerges
2022 → Management Response
2023 → Major Outcome
```

| Field | Purpose |
|---|---|
| Date | Event date |
| Title | Event |
| Description | Explanation |
| Category | Strategic / Financial / etc. |
| Evidence | Supporting source |
| Importance | Low / Medium / High |

## 10. Problem Definition

Distinguish: **Symptom** (what appears wrong) → **Problem** (the actual issue) → **Root Cause** (why it exists) → **Consequence** (what results).

Example chain: *Employee turnover increased → Retention deterioration → Compensation + management + culture → Higher recruitment cost.*

**Problem Statement Builder fields:** problem statement, affected area, severity, urgency, evidence, impact, scope, assumptions.

**Problem Tree** (analysis tool only — never a permanent front-end layout):

```
              MAIN PROBLEM
                   │
     ┌─────────────┼─────────────┐
     ↓             ↓             ↓
   Cause A       Cause B       Cause C
     │             │             │
   Cause A1      Cause B1      Cause C1
```

## 11. Stakeholder Analysis

| Stakeholder | Interest | Influence | Impact | Position |
|---|---|---|---|---|
| Employees | High | Medium | High | Supportive |
| Management | High | High | High | Mixed |
| Customers | High | Medium | High | Concerned |
| Regulators | Medium | High | High | Neutral |

Classification quadrants: high influence/high interest, high influence/low interest, low influence/high interest, low influence/low interest — rendered as a reusable **Stakeholder Matrix** (Manage / Engage / Monitor / Inform).

## 12. Evidence Management

Evidence is one of the most important product features. Types: research papers, annual reports, financial statements, government/regulatory reports, company reports, official websites, datasets, interviews, surveys, news reports, books, case documents.

**The system stores information and source metadata rather than unnecessarily storing permanent copies of every uploaded document** — see §19, Data Extraction Architecture.

### 12.1 Source-First Architecture

Preferred model: `Evidence → Source → Source URL / Citation` — **not** `Upload PDF → Keep PDF forever`. Temporary files may be processed and removed after extraction where appropriate. Persistent value = extracted information, evidence notes, citations, source URL, publication info, page/reference info.

### 12.2 Evidence Record

```
Evidence
├── Evidence ID
├── Case ID
├── Claim
├── Evidence Type
├── Source
├── Source URL
├── Citation
├── Date
├── Reliability
├── Notes
└── Related Finding
```

### 12.3 Evidence Reliability

| Level | Meaning |
|---|---|
| High | Primary/official evidence |
| Medium | Reliable secondary source |
| Low | Unverified/limited evidence |

The plugin never pretends all sources have equal reliability.

### 12.4 Claim-to-Evidence Relationship

```
Claim → Evidence → Source
```

Every major analytical claim can optionally connect to evidence, so the analytical chain stays verifiable.

## 13. Data Within Cases

Quantitative data (revenue, profit, turnover, market share, customer numbers, ratios, survey results, operational KPIs) can be entered manually, via CSV, via XLSX, via extracted tables, or via structured datasets. A data table may become a Gutenberg table/data block.

## 14. Analytical Framework Library

Frameworks are modular — the plugin offers a library rather than forcing one template. New frameworks must be addable without changing the core case engine.

- **SWOT** — Strengths, Weaknesses, Opportunities, Threats
- **PESTLE** — Political, Economic, Social, Technological, Legal, Environmental
- **Porter's Five Forces** — Rivalry, New entrants, Supplier power, Buyer power, Substitutes
- **Value Chain** — Inbound, Operations, Outbound, Marketing, Service
- **McKinsey 7S** — Strategy, Structure, Systems, Shared Values, Skills, Style, Staff

## 15. Root Cause Analysis

Supported methods: 5 Whys, Fishbone/Ishikawa, Cause-effect analysis, Problem Tree.

```
Problem → Why? → Why? → Why? → Root Cause
```

Fishbone default categories: People, Process, Technology, Finance, Management, Environment, Policy, Market (custom categories allowed).

## 16. Decision Analysis

- **Alternatives** — candidate options with pros/cons
- **Decision Matrix** — weighted, scored comparison of alternatives (Cost / Impact / Risk / Feasibility / Score)
- **Risk Analysis / Risk Matrix** — probability × impact × severity × mitigation

## 17. Recommendation Engine

Chain: `Finding → Implication → Recommendation → Action → KPI` — the single most important analytical relationship in the plugin.

Recommendation categories: Strategic, Operational, Financial, HR, Leadership, Technology, Policy, Governance.

### 17.1 Implementation Plan

Fields: action, owner, start, end, status, KPI, resources, dependencies, implementation risks.

### 17.2 Outcome Analysis

Compares expected vs. actual results per metric.

### 17.3 Lessons Learned & Generalizability

Each lesson: statement, category, generalizable (yes/no), related finding.

## 18. Case Comparison / Cross-Case Intelligence

Supports comparing multiple cases and detecting recurring patterns across a case library — a key differentiator from ad-hoc single-document AI analysis.

## 19. Data Extraction, Persistence & Reuse Architecture

### 19.1 Purpose

The plugin extracts structured information from PDFs, financial statements, annual reports, CSV, XLS/XLSX, and other structured/report formats, transforming them into **usable, structured, reusable case intelligence.**

### 19.2 Core Pipeline

```
SOURCE FILE (PDF / XLS / XLSX / CSV / other)
        ↓
TEMPORARY INGESTION
        ↓
VALIDATION
        ↓
EXTRACTION
        ↓
NORMALIZATION
        ↓
STRUCTURED DATA PERSISTENCE
        ↓
VALIDATION / REVIEW
        ↓
REUSABLE DATA LIBRARY
        ↓
TEMPORARY SOURCE FILE CLEANUP
```

### 19.3 Temporary Source File Principle

> **Source File ≠ Persistent Product Data.** Uploaded File = Temporary Ingestion Medium. Extracted + Normalized + Source-Linked Information = Persistent Reusable Asset.

Files may be stored temporarily for validation, parsing, text/table/financial-data/metadata extraction. After successful extraction and persistence, the original uploaded file **may be deleted.** The plugin must not require every source PDF/CSV/XLS/XLSX to remain permanently stored.

### 19.4 What Gets Extracted

- **Structured data:** financial metrics, revenue, profit, expenses, assets, liabilities, equity, cash flow, employee data, operational KPIs, survey results, numerical datasets
- **Tables:** converted into reusable structured rows/columns wherever extraction succeeds
- **Textual information:** facts, statements, observations, evidence, important events, dates, periods, relevant analytical text
- **Source metadata:** title, publisher/organization, source type, publication date, official URL, citation info, page/reference info, reliability level

### 19.5 Normalization

Date/period normalization, numeric normalization, currency normalization, unit normalization, metric naming, category classification. Example:

```
Raw Value: "$130 million"
        ↓
Metric: Revenue | Value: 130000000 | Currency: USD | Unit: Million | Period: FY 2025
```

The relationship between a normalized value and its original source reference is always preserved.

### 19.6 Structured Data Persistence — Core Data Model

```
Source
├── Dataset
├── Extracted Tables
├── Measurements
├── Metrics / KPIs
├── Evidence
└── Claims
```

### 19.7 Source Provenance

Deleting the original file must never delete the origin history of extracted information. Every important extracted record retains traceability:

```
Metric: Revenue | Value: 130,000,000 | Currency: USD | Period: FY 2025
Source: ABC Corporation Annual Report 2025 | Source Type: PDF
Official Source: Yes | Source URL: <official report URL> | Reference: Page 48
Extraction Method: Table Extraction | Validation Status: Verified
```

### 19.8 Extraction & Validation Lifecycle

```
EXTRACTED → REVIEWED → VERIFIED
```

Records may also be marked **REJECTED** or **CORRECTED**. The system distinguishes automatically-extracted, user-reviewed, verified, and manually-corrected information.

### 19.9 Reusability Architecture

```
OFFICIAL SOURCE → Upload Once → Extract → Normalize → Persist Structured
Information → Delete Temporary File → Reuse Data
    ├── Case A
    ├── Case B
    ├── Case C
    ├── Analysis
    ├── KPIs
    ├── Evidence
    └── Gutenberg Publication
```

A single source can feed multiple cases and analytical processes without a re-upload.

### 19.10 Source File Cleanup Rule

A temporary file **may be deleted** once: validation completed; extraction completed successfully; required structured information persisted; source metadata retained; provenance/reference retained; extraction status recorded.

A failed or incomplete extraction must **never** be treated as successfully persisted information. Temporary files must also be handled correctly on: extraction failure, parsing failure, interrupted processing, server timeout, abandoned upload.

### 19.11 Final Extraction Workflow

```
EXTRACT → NORMALIZE → VALIDATE → PERSIST → REUSE → CLEANUP TEMPORARY SOURCE FILE
```

The long-term product asset is the extracted structured data, normalized measurements, reusable datasets, extracted tables, metrics/KPIs, evidence, claims, source metadata, citations, source URLs, page/reference info, and validation/provenance records — **not necessarily the original file.**

## 20. Publication Blueprints

Blueprint types: Academic, Business, HR, Leadership, Financial, Strategic, Comparative case publications. Blueprints define **recommended article structure**; a Block represents **actual content**.

> A blueprint must never lock the user into a fixed template. The user can freely reorder, add, or remove Gutenberg blocks after a blueprint is applied.

Example blueprint → Gutenberg reorder:

```
Blueprint: Executive Summary → Problem → Evidence → Analysis → Recommendation
User edits to: Problem → Evidence → Chart → Analysis → Quote → Recommendation
```

## 21. Gutenberg Publishing Architecture

```
Case → Structured Analysis → Verified Findings → Publication Blueprint →
Gutenberg → Native WordPress Post
```

The final post remains fully editable by the user in the native Block Editor.

### 21.1 Custom Blocks (initial library)

| Block | Purpose |
|---|---|
| SI Case Summary | Case overview |
| SI Case Context | Background |
| SI Problem | Problem statement |
| SI Stakeholders | Stakeholder matrix |
| SI Timeline | Case timeline |
| SI Evidence | Evidence display |
| SI Case Data | Data table |
| SI Root Cause | Root-cause analysis |
| SI SWOT | SWOT |
| SI PESTLE | PESTLE |
| SI Five Forces | Competitive analysis |
| SI 7S | McKinsey 7S |
| SI Fishbone | Fishbone analysis |
| SI 5 Whys | Root-cause analysis |
| SI Decision Matrix | Alternative evaluation |
| SI Risk Matrix | Risk analysis |
| SI Recommendation | Recommendation |
| SI Implementation | Action plan |
| SI Outcome | Outcome |
| SI Lessons | Lessons learned |
| SI Case Source | Source/citation |

Each block must be independent, editable, reusable, responsive, accessible, theme-compatible, and context-aware. Important controls live in the block interface; advanced controls live in the block sidebar (per WordPress's own block UI guidance).

### 21.2 No Shortcode Dependency

The primary publishing system must not depend on `[case_study]`, `[kpi]`, `[analysis]`-style shortcodes. Gutenberg blocks are serialized into post content and stay part of the native editor workflow. Shortcodes are supported only for compatibility where strictly necessary.

### 21.3 Native WordPress Publishing

The final publication is a normal WordPress post and must support: Gutenberg, revisions, categories, tags, author, featured image, excerpt, slug, comments, scheduling. **The plugin must never replace WordPress's own publishing system.**

### 21.4 Categories & Tags

Native taxonomies only — e.g., Category: *Case Studies*; Tags: *Leadership, Banking, HRM, Strategy, Risk Management, Bangladesh.*

### 21.5 Block/Blueprint Technical Principles

Every block follows modern Block API architecture:

```
block/
├── block.json
├── index.js
├── edit.js
├── save.js
├── render.php
├── style.css
└── editor.css
```

Dynamic (server-rendered) blocks are used where content benefits from server-side rendering. The plugin never builds one giant "SI Case Study" block; each analytical concept owns its own block, preserving Gutenberg's modular model.

## 22. SEO Integration

```
WordPress Post → SEO Adapter → { Yoast | Rank Math }
```

The plugin assists with SEO title, meta description, focus keyword, semantic headings, article structure, internal-linking suggestions, source links, readability — **it must never duplicate or replace Yoast/Rank Math.**

## 23. Case Citation System & Source Verification

Every source can publish as an inline source, footnote-style reference, source box, or reference list: `Finding → Evidence → Source → URL`. Source status is tracked distinctly: *Unverified, Checked, Official, Archived, Broken URL* — the plugin never claims a source is verified when it hasn't actually been checked.

## 24. AI Integration

AI assists the analyst; it never replaces the case-analysis engine.

**AI may:** summarize case evidence; identify potential problems; suggest frameworks; suggest stakeholders; draft findings; suggest alternatives; draft recommendations; summarize lessons; compare cases; improve writing.

**AI must NOT:** invent evidence; fabricate sources; fabricate case facts; invent financial figures; invent interviews; invent outcomes; present assumptions as facts.

### 24.1 AI Evidence Workflow

```
Correct:   AI → Evidence → Suggested Interpretation → Human Review → Confirmed Finding → Publication
Incorrect: AI → Unverified Claim → Automatic Publication
```

### 24.2 AI-Assisted Case Creation

```
User enters case information
        ↓
AI assists with: context extraction, problem candidates, stakeholder
candidates, framework suggestions, evidence organization
        ↓
Human confirms
        ↓
Structured Case
```

All AI suggestions remain editable, and all AI-generated content remains reviewable before publication.

## 25. Case Quality & Publication Readiness

### 25.1 Case Quality Score (guidance indicator, not academic certification)

| Dimension | Weight |
|---|---|
| Problem clarity | 15% |
| Evidence quality | 20% |
| Analytical depth | 20% |
| Stakeholder analysis | 10% |
| Alternatives | 10% |
| Recommendation quality | 10% |
| Implementation | 10% |
| Sources | 5% |

Weights must be configurable.

### 25.2 Evidence Coverage

```
Evidence Coverage = (Supported Claims / Total Major Claims) × 100
```

### 25.3 Case Completeness

Dashboard progress bar across Context, Problem, Stakeholders, Evidence, Analysis, Alternatives, Recommendation, Outcome, Sources.

### 25.4 Publication Readiness

Composite of Evidence Coverage, Case Completeness, Source Coverage, Recommendation Support → overall status such as "Ready for Review." This is a **guidance system, not an automatic publication authority.**

### 25.5 Case Quality Check (pre-publish gate, human-confirmed)

Problem defined · Evidence attached · Sources recorded · Root cause analyzed · Alternatives considered · Recommendation supported · Risks identified · Implementation defined · Limitations included · Sources available.

## 26. Admin Architecture (summary — full spec in `CSAP_UI.md`)

Top-level menu: **SI Case Analysis** → Dashboard, Cases (+ Case Workspace), Sources Library, Tools, Settings, License.

> Critical rule: Evidence, Analysis, Risks, Recommendations, Findings, and Decisions do **not** get their own top-level menus. They live inside the contextual Case Workspace (§27).

## 27. Case Workspace Principle

Do not build 20 disconnected admin pages requiring constant navigation. The Case Workspace is the primary operational screen, with contextual tabs:

```
Case
 ├── Overview
 ├── Context
 ├── Problem
 ├── Stakeholders
 ├── Evidence
 ├── Data
 ├── Analysis
 ├── Decision
 ├── Recommendations
 ├── Implementation
 ├── Outcome & Lessons
 └── Publication
```

## 28. Database Architecture

Recommended logical entities: Cases, Case Metadata, Case Timelines, Stakeholders, Sources, Evidence, Claims, Data Tables, Framework Analyses, Root Causes, Alternatives, Decision Matrices, Risks, Recommendations, Implementation Plans, Outcomes, Findings, Lessons, Publishing Records.

All persistence goes through SI Framework v3.0 repositories/services — **no module may access `$wpdb` directly outside its Repository class.**

## 29. Ownership Architecture

```
Request → Capability → Nonce → Validation → OwnershipManager → Repository → Mutation
```

No module may bypass `OwnershipManager`.

### 29.1 Case Isolation

```
CASE A                          CASE B
 ├── Evidence A                  ├── Evidence B
 ├── Analysis A                  ├── Analysis B
 ├── Findings A                  ├── Findings B
 └── Recommendations A           └── Recommendations B
```

Case A must never modify Case B.

### 29.2 WordPress Post Isolation

The plugin must **never**: modify unrelated posts; delete unrelated posts; overwrite unrelated content; change unrelated categories/tags; alter another plugin's post metadata. Publishing is always explicitly initiated:

```
Correct:   Case → Create Draft → User Reviews → Publish
Incorrect: Save Case → Automatically rewrite WordPress posts
```

**Critical regression test (must always pass):** given existing WordPress Posts A, B, C, creating Case X, analyzing Case X, and creating a draft from Case X must leave Posts A, B, and C completely unchanged. This directly guards against the destructive behavior seen in the previous template-based plugin generation.

## 30. License Architecture (summary — full spec in `CSAP_php_directory.md`)

```
SI Case Study Analysis Pro → LicenseController → Product-specific SDK →
LicenseState → FeatureManager → Premium Case Features
```

Modules never access licensing SDK internals directly.

### 30.1 Premium Feature Gating (indicative — finalize after MVP validation)

| Feature | Free | Premium |
|---|:---:|:---:|
| Basic Case Workspace | ✓ | ✓ |
| Basic Case Structure | ✓ | ✓ |
| Basic Sources | ✓ | ✓ |
| Basic Evidence | ✓ | ✓ |
| Timeline | ✓ | ✓ |
| Stakeholder Analysis | ✓ | ✓ |
| Advanced Frameworks | — | ✓ |
| Root Cause Tools | — | ✓ |
| Decision Matrix | — | ✓ |
| Advanced Risk Analysis | — | ✓ |
| Cross-Case Analysis | — | ✓ |
| Advanced Findings | — | ✓ |
| Implementation Planner | — | ✓ |
| AI Assistance | — | ✓ |
| Advanced Gutenberg Blocks | — | ✓ |
| Advanced Publication Blueprints | — | ✓ |

## 31. Full Architecture

```
                    SI FRAMEWORK v3.0
                           │
          ┌────────────────┼────────────────┐
          │                │                 │
         CORE            ENGINE           PREMIUM
          │                │                 │
          │        ┌───────┼────────┐        │
          │        │       │        │        │
          │   Ownership Feature  Renderer  License
          │    Manager   Manager           Controller
          │
          ▼
                 CASE INTELLIGENCE
                         │
       ┌─────────────────┼──────────────────┐
       │                 │                  │
       ▼                 ▼                  ▼
     CASE             EVIDENCE           ANALYSIS
       │                 │                  │
       │                 │        ┌─────────┼─────────┐
       │                 │        │         │         │
       │                 │      Root      Framework  Decision
       │                 │      Cause
       │                 │
       └─────────────────┼──────────────────┘
                          ▼
                   RECOMMENDATION
                          ▼
                    IMPLEMENTATION
                          ▼
                       FINDINGS
                          ▼
                 PUBLICATION BLUEPRINT
                          ▼
                       GUTENBERG
                          ▼
                NATIVE WORDPRESS POST
```

## 32. Example Real-World Workflow

*"Why Did a Bangladeshi Bank Experience a Major Increase in Non-Performing Loans?"*

```
Create Case → Banking Industry → Bangladesh → 2020–2025 →
Add Annual Reports → Add Regulatory Sources → Add Financial Data →
Define Problem → Build Timeline → Identify Stakeholders →
Analyze NPL Trend → Apply PESTLE → Apply SWOT →
Perform Root Cause Analysis → Identify Strategic Alternatives →
Build Risk Matrix → Develop Recommendations → Define Implementation
```

Resulting article outline: Executive Summary → Case Context → Banking Environment → Case Timeline → Central Problem → Stakeholder Analysis → Evidence → NPL Data → Financial Impact → Root Cause Analysis → PESTLE → SWOT → Strategic Alternatives → Decision Matrix → Risk Assessment → Recommended Strategy → Implementation Plan.

The plugin builds the analytical foundation; **Gutenberg remains the final publishing canvas.**

## 33. Product Differentiation

Not: *"A plugin for creating case-study templates."* (recreates the previous product's weakness)

Instead: **"Build evidence-based case studies from problem to recommendation."**

Value stack: Case Data + Evidence + Sources + Analytical Frameworks + Root Cause + Decision Analysis + Risk + Recommendations + Implementation + Findings + Gutenberg Publishing.

### 33.1 Competitive Advantages

1. Structured Case Intelligence — the case is more than an article
2. Evidence Traceability — claims link to evidence and sources
3. Analytical Frameworks — SWOT, PESTLE, Five Forces, 7S, root-cause, etc.
4. Decision Analysis — alternatives evaluated systematically
5. Recommendation Chain — Problem → Evidence → Finding → Recommendation → Action
6. Cross-Case Intelligence — multiple cases compared
7. Gutenberg-Native Publishing — no proprietary article editor
8. Source Preservation — information survives, unnecessary uploaded files don't have to
9. AI Assistance — supports analysis rather than pretending to be the source of truth

### 33.2 Difference from a Generic AI Chat

```
CASE → EVIDENCE → SOURCE → ANALYSIS → FRAMEWORK → DECISION →
RECOMMENDATION → IMPLEMENTATION → FINDING → PUBLICATION
```

The user can return months later and continue the same case — persistent, structured, evidence-linked analytical state is the product, not a one-off chat transcript.

## 34. Final Engineering Principle

> Do not build another template engine. Build a **Case Intelligence Engine with a Gutenberg Publishing Layer.**

The Case Engine determines *what happened, why it happened, what evidence supports the explanation, what alternatives exist, what risks exist, what decision is appropriate, what should be recommended, how it should be implemented, and what lessons can be learned.* Gutenberg determines *how the verified case analysis becomes a professional WordPress publication.*

## 35. Development Roadmap

| Phase | Scope |
|---|---|
| 1 — Framework Foundation | SI Framework v3.0 Core, Container, Bootstrap, Loader, EngineManager, OwnershipManager, FeatureManager, LicenseController, product SDK integration |
| 2 — Case Foundation | Cases, case metadata, case status, case ownership, case workspace |
| 3 — Sources & Evidence | Sources, Evidence, Claims, source metadata, evidence relationships, evidence reliability |
| 4 — Context & Problem | Context, Timeline, Problem statement, Problem tree, Stakeholders, Stakeholder matrix |
| 5 — Analytical Frameworks | SWOT, PESTLE, Five Forces, Value Chain, 7S (modular; new frameworks addable without touching the core case engine) |
| 6 — Root Cause | 5 Whys, Fishbone, Cause-effect, root-cause records |
| 7 — Decision Intelligence | Alternatives, criteria, weights, ratings, decision matrix, decision record |
| 8 — Risk | Risk register, probability, impact, severity, mitigation, risk matrix |
| 9 — Recommendation & Implementation | Recommendations, rationale, evidence, action plans, owners, timelines, KPIs, outcomes |
| 10 — Findings & Lessons | Findings, evidence relationships, implications, lessons, generalizability |
| 11 — Gutenberg | All case-study blocks (summary, context, problem, evidence, timeline, stakeholder, analysis, decision, risk, recommendation, implementation, outcome, lessons, source) |
| 12 — Publication Blueprints | Academic, business, HR, leadership, financial, strategic, comparative (blueprints remain optional) |
| 13 — AI Assistance | Evidence summarization, problem suggestions, framework suggestions, finding drafting, recommendation drafting, case comparison (all reviewable) |
| 14 — SEO Integration | Yoast and Rank Math adapters (no duplicated SEO engine) |
| 15 — Security | Capability checks, nonces, sanitization, escaping, REST/AJAX permissions, ownership, case/source/post isolation, upload security, temp-file cleanup |
| 16 — Production QA | Installation, license lifecycle, case CRUD, evidence CRUD, analysis modules, publishing pipeline (see §36) |

## 36. Production QA Checklist

- **Installation:** activation, deactivation, uninstall, upgrade
- **License:** activation, verification, deactivation, invalid key, expired state, network failure, SDK failure
- **Case:** create, edit, duplicate, archive, delete
- **Evidence:** create, update, source linking, claim linking
- **Analysis:** frameworks, root cause, alternatives, decisions, risks
- **Publishing:** create draft, Gutenberg editing, revisions, categories, tags, author, featured image, SEO compatibility
- **Regression:** the critical post-isolation scenario in §29.2 must always pass

## 37. Security Principle

```
Capability + Nonce + Sanitization + Validation + Ownership + Authorization + Escaping
```

Every mutation passes through the appropriate application/service layer — never directly from a controller to `$wpdb`.

## 38. Architecture Rules (non-negotiable)

1. Ownership before functionality.
2. `FeatureManager` is the entitlement gateway.
3. Modules never access licensing SDK internals.
4. Gutenberg is the publishing layer.
5. Case analysis is the intelligence layer.
6. Evidence must be traceable to sources.
7. AI cannot fabricate evidence.
8. WordPress posts remain native.
9. No automatic rewriting of unrelated posts.
10. Blueprints never lock the user into a fixed article.

## 39. Final Development Rule

The fresh build must permanently preserve these inequalities:

```
Case Intelligence   ≠ Article Template
Evidence             ≠ Uploaded File
Analysis             ≠ Gutenberg Content
Blueprint            ≠ Locked Template
AI                   ≠ Statistical/Case Truth
Case                 ≠ WordPress Post

Gutenberg            = Publishing Layer
SI Framework v3.0    = Application Foundation
FeatureManager       = Premium Entitlement Gateway
LicenseController    = Licensing Boundary
OwnershipManager     = Data Isolation Boundary
Native WordPress     = Publishing Foundation
```

This architecture eliminates the fundamental weaknesses of the previous template-engine implementation and gives SI Case Study Analysis Pro a clearer product identity and a stronger premium-market proposition.

---

**Companion documents:**
- `CSAP_php_directory.md` — file-by-file PHP directory, module dependencies, licensing SDK wiring, data-extraction pipeline files
- `CSAP_UI.md` — full admin menu and Case Workspace UI specification
- `CSAP_AI_Coding.md` — coding standards, security guardrails, and behavioral rules for any AI assistant working in this codebase
