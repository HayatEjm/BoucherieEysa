// assets/bootstrap.js
import { startStimulusApp } from '@symfony/stimulus-bridge';

// (si tu as @symfony/autoimport, tu peux aussi garder la ligne suivante)
// import '@symfony/autoimport';

export const app = startStimulusApp(require.context(
  '@symfony/stimulus-bridge/lazy-controller-loader!.',
  true,
  /\.(j|t)sx?$/
));

// 👉 N'AJOUTE PAS une autre ligne du type "const app = startStimulusApp();"
// 👉 N'IMPORTE PAS une seconde fois startStimulusApp
