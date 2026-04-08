import path from 'node:path';
import tailwindcss from '@tailwindcss/vite';
import { defineConfig } from 'vite';

const stylesEntry = path.resolve(__dirname, 'src/styles/app.css');

export default defineConfig({
  publicDir: path.resolve(__dirname, 'public'),
  plugins: [tailwindcss()],
  resolve: {
    alias: {
      '@': path.resolve(__dirname, './src'),
    },
  },
  server: {
    host: '0.0.0.0',
    port: 5173,
    strictPort: true,
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
    outDir: path.resolve(__dirname, 'public/assets'),
    emptyOutDir: false,
    rollupOptions: {
      input: {
        app: stylesEntry,
      },
      output: {
        assetFileNames: (assetInfo) => {
          if (assetInfo.name?.endsWith('.css')) {
            return 'css/[name][extname]';
          }

          return 'assets/[name]-[hash][extname]';
        },
        entryFileNames: 'js/[name].js',
        chunkFileNames: 'js/[name]-[hash].js',
      },
    },
  },
});

