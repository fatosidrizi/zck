# PRD — ZCK Community Platform (Laravel + MySQL)

> Office for Community Issues — Prime Minister's Office, Kosovo
> Language: English first, multilingual later (AL, SR, RO, BS, TR)

---

## Phase 1: Project Setup & Foundation

- [x] Initialize Laravel project
- [x] Configure MySQL database connection
- [x] Set up environment (.env) configuration
- [x] Install Filament admin panel
- [x] Set up authentication (Filament built-in)
- [x] Configure basic role system (Super Admin, Admin, Editor, User)
- [x] Set up Tailwind CSS frontend
- [x] Create base layout (header, footer, navigation)
- [ ] Deploy structure on Virtualmin server

---

## Phase 2: Public Frontend — Static Pages

- [x] Homepage with hero section, mission statement, stats dashboard
- [x] About Us page (mission, vision, organizational structure, activities)
- [x] Contact page with contact form + office info
- [x] Navigation menu (Home, About, NGOs, News, Communities, Public Calls, Contact)
- [x] Responsive mobile design
- [x] Footer with links to external resources (Ombudsman, Legal Aid, Language Commissioner, etc.)

---

## Phase 3: News & Publications Module

- [x] News model + migration (title, slug, body, image, category, published_at)
- [x] News listing page with pagination
- [x] Single news detail page
- [x] News categories (News, Bulletins, Reports)
- [x] Admin: CRUD for news articles
- [x] Admin: Image upload for news
- [x] Admin: Publish/draft status toggle
- [x] Frontend: Filter by category (Bulletins, Reports)

---

## Phase 4: Public Calls Module

- [x] Public Calls model + migration (title, slug, body, deadline, type, attachment)
- [x] Public Calls listing page with pagination
- [x] Single public call detail page
- [x] Call types (Recruitment, Grants, Funding, Commissions)
- [x] Admin: CRUD for public calls
- [x] Admin: File/PDF attachment upload
- [x] Frontend: Show open/closed status based on deadline
- [x] Frontend: Filter by call type

---

## Phase 5: NGO Directory

- [x] NGO model + migration (name, description, logo, contact, website, location, category)
- [x] NGO listing page with search and filter
- [x] Single NGO profile page
- [x] Admin: CRUD for NGOs
- [x] Admin: Logo upload
- [x] Frontend: Search by name, location, category

---

## Phase 6: Communities Module

- [x] Community model + migration (name, description, image, population, region)
- [x] Communities listing page
- [x] Single community detail page
- [x] Admin: CRUD for communities
- [x] Link community events to community pages

---

## Phase 7: Events Calendar

- [x] Event model + migration (title, description, date, time, location, community_id, image)
- [x] Events listing page (upcoming/past)
- [x] Single event detail page
- [x] Admin: CRUD for events
- [x] Frontend: Calendar view with monthly navigation
- [x] Frontend: Filter by community

---

## Phase 8: Discrimination Reporting System

- [x] Report model + migration (reporter_name, email, phone, type, description, location, date_of_incident, evidence_file, status)
- [x] Public report submission form (accessible without login)
- [x] Report confirmation page with tracking code
- [x] Admin: View all reports with status (New, In Review, Resolved, Dismissed)
- [x] Admin: Assign report to staff member
- [x] Admin: Add internal notes to a report
- [x] Admin: Change report status with history tracking (StatusHistory model + RelationManager)
- [x] Admin: Export reports to CSV
- [x] Reporter can check status with a tracking code (no login needed)

---

## Phase 9: User Registration & Accounts

- [x] User registration page (/signup)
- [x] Login / Logout (/login, /logout)
- [x] User profile page (/profile) with edit name/email
- [x] Password reset via email (/forgot-password, /reset-password)
- [x] Admin: Manage users (list, activate, deactivate, assign roles)

---

## Phase 10: Statistics Dashboard (Homepage)

- [x] Count registered NGOs
- [x] Count communities
- [x] Count events held
- [x] Count donors (placeholder)
- [x] Count public calls published
- [x] Animated counter on homepage (IntersectionObserver)
- [ ] Admin: Optional manual override for stats

---

## Phase 11: Multilingual Support (Post-English)

- [ ] Set up Laravel localization structure (lang/en, lang/sq, lang/sr, etc.)
- [ ] Database: translatable fields on all content models
- [ ] Language switcher in header
- [ ] Translate UI strings for: Albanian, Serbian, Romanian, Bosnian, Turkish
- [ ] Admin: Enter translations per content item
- [ ] SEO: hreflang tags and localized URLs

---

## Phase 12: SEO, Performance & Security

- [x] Meta tags (title, description, OG tags) per page
- [x] Sitemap.xml generation (/sitemap.xml)
- [x] Image lazy loading (loading="lazy" on images)
- [x] CSRF protection (built-in)
- [x] Rate limiting on forms (throttle middleware on POST routes)
- [x] HTTPS enforcement (ForceHttps middleware for production)
- [ ] Backup strategy (database + uploads)

---

## Phase 13: Deployment & Go-Live

- [ ] Set up production environment on Virtualmin server
- [ ] Configure domain and SSL
- [ ] Migrate content from old WordPress site
- [ ] Test all features end-to-end
- [ ] Set up cron jobs (scheduled tasks, backups)
- [ ] Go live and redirect old domain
- [ ] Post-launch monitoring

---

## External Resource Links (Footer)

- [x] Agency for Legal Aid
- [x] Ombudsman Office
- [x] Language Commissioner
- [x] Roma, Ashkali, Egyptian community platform
- [x] Government of Kosovo
- [x] Prime Minister's Office
