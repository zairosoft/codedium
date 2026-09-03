import path from 'node:path';
import { fileURLToPath } from 'node:url';
import { defineConfig } from 'vitest/config';

const projectRoot = path.dirname(fileURLToPath(import.meta.url));

export default defineConfig({
  resolve: {
    alias: {
      '@': path.resolve(projectRoot, './src'),
      '@app': path.resolve(projectRoot, './src/app'),
      '@modules': path.resolve(projectRoot, './src/modules'),
    },
  },
  test: {
    environment: 'node',
    include: ['src/**/*.spec.ts', 'src/**/*.test.ts'],
    exclude: ['dist/**', 'public/**'],
  },
});
