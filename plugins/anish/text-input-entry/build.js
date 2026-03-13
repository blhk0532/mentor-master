import * as esbuild from "esbuild";

mkdirSync("dist", { recursive: true });

esbuild.build({
  entryPoints: ["resources/js/text-input-entry.js"],
  outfile: "dist/text-input-entry.js",
  bundle: true,
  mainFields: ["module", "main"],
  platform: "neutral",
  treeShaking: true,
  target: ["es2020"],
  allowOverwrite: true,
  minify: true,
});
