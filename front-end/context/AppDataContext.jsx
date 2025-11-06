import { createContext, useContext, useEffect, useRef, useState } from 'react';

const AppDataCtx = createContext(null);

const defaultData = {
  menu: { items: [] },
  footer: { items: [] },
  categories: { tree: [] },
  ts: 0,
};

export function AppDataProvider({ initial, children }) {
  const [state, setState] = useState(initial || null);
  const abortRef = useRef(null);

  useEffect(() => {
    if (state || typeof window === 'undefined') {
      return;
    }

    abortRef.current = new AbortController();
    const { signal } = abortRef.current;

    (async () => {
      try {
        const res = await fetch('/api/app-data', { signal });
        const data = res.ok ? await res.json() : defaultData;
        if (!signal.aborted) {
          setState(data);
        }
      } catch {
        if (!signal.aborted) setState(defaultData);
      }
    })();

    return () => {
      if (abortRef.current) abortRef.current.abort();
    };
  }, [state]);

  return (
    <AppDataCtx.Provider value={state || defaultData}>
      {children}
    </AppDataCtx.Provider>
  );
}

export function useAppData() {
  return useContext(AppDataCtx) || defaultData;
}
