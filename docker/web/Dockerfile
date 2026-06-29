# SP-MIS web (React + TypeScript) — Vite dev server for local development.
FROM node:24-alpine

WORKDIR /app

# Dependencies are installed at container start (entrypoint) so a bind-mounted
# source tree on a fresh clone works without a prior host-side `npm install`.
COPY docker/web/entrypoint.sh /usr/local/bin/entrypoint
RUN chmod +x /usr/local/bin/entrypoint

EXPOSE 5173

ENTRYPOINT ["entrypoint"]
CMD ["npm", "run", "dev", "--", "--host", "0.0.0.0", "--port", "5173"]
