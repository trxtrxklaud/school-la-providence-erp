import react from '@vitejs/plugin-react';
import path from 'path';
import { defineConfig } from 'vite';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig(() => {
  return {
    plugins: [
      react(),
      tailwindcss(),
    ],

    resolve: {
      alias: {
        '@': path.resolve(__dirname, '.'),
      },
    },

    server: {
      host: '0.0.0.0',
      port: 3000,

      hmr: process.env.DISABLE_HMR !== 'true',

      proxy: {
        '/api': {
          target: 'http://127.0.0.1:8001',
          changeOrigin: true,
        },
      },

      watch: {
        usePolling: true,
        interval: 1000,
        ignored: [
          '**/vendor/**',
          '**/node_modules/**',
        ],
      },
    },
  };
});
