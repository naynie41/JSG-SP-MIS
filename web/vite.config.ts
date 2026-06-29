import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'

// https://vite.dev/config/
export default defineConfig({
  plugins: [react()],
  server: {
    // Bind to all interfaces so the dev server is reachable from outside the
    // container, and enable polling for reliable HMR over Docker bind mounts.
    host: true,
    port: 5173,
    watch: { usePolling: true },
  },
})
