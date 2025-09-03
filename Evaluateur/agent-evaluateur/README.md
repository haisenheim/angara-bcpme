# agent-evaluateur

AdonisJS v6 (TypeScript) microservice for dynamic evaluation of cacao scoring grids.

## Features
- Receives a JSON grid (categories → sub-criteria → values)
- Converts sub-criteria to scores (1–10) using binning and enum mapping
- Applies weights and calculates total score out of 10
- Returns: score10, SME risk class (configurable thresholds), strengths/weaknesses (Top 5 by weight × score), detailed breakdown with interpretation
- Authenticates requests via HMAC (X-Timestamp, X-Signature headers)
- Endpoints: POST /evaluate, POST /evaluate/batch, GET /health

## Getting Started
1. Install dependencies:
   ```sh
   npm install
   ```
2. Start the development server:
   ```sh
   node ace serve --watch
   ```

## Project Structure
- `app/` — Main application code
- `start/routes.ts` — API route definitions
- `config/` — Configuration files

## Customization
See `.github/copilot-instructions.md` for workspace-specific Copilot instructions.
