import { createRoot, StrictMode } from '@wordpress/element';
import Home from '@pages/home'

import { ToastProvider } from '@blocks/toast/ToastContext';

createRoot(document.getElementById('rhef-dashboard')!).render(
  <StrictMode>
    <ToastProvider>
      <Home />
    </ToastProvider>
  </StrictMode>,
)