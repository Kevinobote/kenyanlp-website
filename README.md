# KenyaNLP Website

Official website for **KenyaNLP** - a Masakhane-affiliated research community advancing NLP, benchmarking, and AI tools for Kenya's 40+ indigenous languages.

## Tech Stack

- **Framework:** [Astro](https://astro.build/) v6 (static site generator)
- **Styling:** [Tailwind CSS](https://tailwindcss.com/) v4
- **Fonts:** Sora, DM Serif Display, JetBrains Mono (Google Fonts)
- **SEO:** Auto-generated sitemap, Open Graph, structured data (JSON-LD)
- **Form backend:** PHP (for cPanel deployment)
- **Deployment target:** cPanel (static HTML upload)

## Project Structure

```
kenyanlp/
├── src/
│   ├── layouts/
│   │   └── Base.astro          # Shared layout (head, nav, footer, SEO meta)
│   ├── components/
│   │   ├── Nav.astro           # Responsive navigation with mobile menu
│   │   ├── Footer.astro        # Site footer
│   │   ├── Card.astro          # Reusable card component
│   │   └── SectionHeader.astro # Reusable section header
│   ├── pages/
│   │   ├── index.astro         # Home page
│   │   ├── about.astro         # About page
│   │   ├── projects.astro      # Projects page (filterable, expandable)
│   │   ├── team.astro          # Team page (photos, bios, LinkedIn)
│   │   ├── community.astro     # Community page
│   │   └── contact.astro       # Contact form page
│   └── styles/
│       └── global.css          # Tailwind config + custom design tokens
├── public/
│   ├── team/                   # Team member photos
│   ├── api/
│   │   └── contact.php         # PHP form handler (for cPanel)
│   ├── robots.txt              # SEO crawl directives
│   ├── favicon.svg
│   └── favicon.ico
├── astro.config.mjs            # Astro + Tailwind + Sitemap config
├── package.json
├── tsconfig.json
└── IMPROVEMENT_PLAN.txt        # Detailed content/layout improvement roadmap
```

## Prerequisites

- **Node.js v22.12.0 or higher** (even-numbered versions only)
- npm (comes with Node.js)

### Check your Node version

```bash
node --version
```

If below v22, install via [nvm](https://github.com/nvm-sh/nvm):

```bash
nvm install 22
nvm use 22
```

## Setup

```bash
# 1. Clone the repo
git clone https://github.com/YOUR_USERNAME/kenyanlp-website.git
cd kenyanlp-website

# 2. Install dependencies
npm install

# 3. Start dev server
npm run dev
```

The site will be available at `http://localhost:4321`

## Available Commands

| Command         | Action                                      |
|-----------------|---------------------------------------------|
| `npm run dev`   | Start local dev server at localhost:4321     |
| `npm run build` | Build production site to `./dist/`           |
| `npm run preview` | Preview the build locally before deploying |

## Deployment to cPanel

1. Update `site` in `astro.config.mjs` to your actual domain
2. Update `$to` email in `public/api/contact.php`
3. Build the site:
   ```bash
   npm run build
   ```
4. Upload **everything inside `dist/`** to your cPanel `public_html/` directory via File Manager or FTP
5. Ensure PHP is enabled on your cPanel (for the contact form)

### Post-deployment checklist

- [ ] Verify all pages load correctly
- [ ] Test the contact form sends emails
- [ ] Check cPanel Email Deliverability settings
- [ ] Add SPF record for your domain (for email reliability)
- [ ] Verify sitemap at `yourdomain.com/sitemap-index.xml`
- [ ] Submit sitemap to Google Search Console

## Design System

### Colors

| Token       | Hex       | Usage                    |
|-------------|-----------|--------------------------|
| green-600   | `#3B6D11` | Primary accent, CTAs     |
| green-900   | `#173404` | Dark backgrounds         |
| teal-50     | `#E1F5EE` | Highlight boxes          |
| amber-50    | `#FAEEDA` | Warning/planning badges  |
| coral-50    | `#FAECE7` | Accent backgrounds       |

### Typography

- **Headings:** DM Serif Display (serif)
- **Body:** Sora (sans-serif)
- **Code/numbers:** JetBrains Mono (monospace)

## Contributing

1. Fork the repository
2. Create a feature branch: `git checkout -b feature/your-feature`
3. Make your changes
4. Build to verify: `npm run build`
5. Commit: `git commit -m "Add your feature"`
6. Push: `git push origin feature/your-feature`
7. Open a Pull Request

## Team

| Name | Role | Institution |
|------|------|-------------|
| Dr. Everlyn Asiko | Co-Lead & Evaluation Pipeline Lead | Lelapa AI |
| Dr. Clemencia Siro | Co-Lead & Data Sourcing Lead | CWI Amsterdam |
| Cynthia Amol | Dataset Cleaning Lead | Maseno University |
| Naome Etori | Model Research & Human Eval Lead | - |
| Kevin Obote | Dataset Evaluation Lead | Guild Code |
| Millicent Ochieng' | Writing & Publications Lead | Microsoft Research Africa |
| Amos Mwendwa | Website & Outreach Lead | Meraki Digital Solutions |

## License

This project is open source under the [MIT License](LICENSE).

---

Built with ❤️ for Kenyan languages.
