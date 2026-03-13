import { defineConfig } from 'vite'

export default defineConfig({
  build: {
    outDir: 'dist',
    emptyOutDir: true,
    lib: {
      entry: {
        'ui-switcher': 'resources/js/ui-switcher.js',
      },
      formats: ['es'],
      fileName: (format, entryName) => `${entryName}.js`,
    },
    rollupOptions: {
      output: {
        assetFileNames: (assetInfo) => {
          if (assetInfo.name.endsWith('.css')) {
            return 'ui-switcher.css';
          }
          return assetInfo.name;
        },
      },
      external: [],
    },
    cssCodeSplit: false,
  },
})
