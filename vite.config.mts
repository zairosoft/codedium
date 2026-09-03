import path from 'node:path';
import { fileURLToPath } from 'node:url';
import tailwindcss from '@tailwindcss/vite';
import { defineConfig } from 'vite';

const projectRoot = path.dirname(fileURLToPath(import.meta.url));
const stylesEntry = path.resolve(projectRoot, 'public/assets/css/app.css');

export default defineConfig({
  publicDir: false,
  plugins: [tailwindcss()],
  resolve: {
    alias: {
      '@': path.resolve(projectRoot, './src'),
      '@app': path.resolve(projectRoot, './src/app'),
      '@modules': path.resolve(projectRoot, './src/modules'),
    },
  },
  server: {
    host: '0.0.0.0',
    port: 5173,
    strictPort: true,
    cors: true,
    proxy: {
      '/api/v1': {
        target: 'http://localhost:3000',
        changeOrigin: true,
        ws: true,
      },
    },
  },
  preview: {
    host: '0.0.0.0',
    port: 4173,
    strictPort: true,
  },
  build: {
    outDir: path.resolve(projectRoot, 'public/assets'),
    emptyOutDir: false,
    rollupOptions: {
      input: {
        app: stylesEntry,
      },
      output: {
        assetFileNames: (assetInfo) => {
          if (assetInfo.name?.endsWith('.css')) {
            return 'css/tailwindcss[extname]';
          }

          return 'assets/[name]-[hash][extname]';
        },
        entryFileNames: 'js/[name].js',
        chunkFileNames: 'js/[name]-[hash].js',
      },
    },
  },
});
