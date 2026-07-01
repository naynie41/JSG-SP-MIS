/**
 * Bearer token persistence. Kept in localStorage so a refresh keeps the session;
 * cleared on logout or when the server rejects the token (401 → redirect).
 */
const TOKEN_KEY = 'spmis.token'

export const tokenStore = {
  get(): string | null {
    try {
      return localStorage.getItem(TOKEN_KEY)
    } catch {
      return null
    }
  },
  set(token: string): void {
    try {
      localStorage.setItem(TOKEN_KEY, token)
    } catch {
      /* ignore storage failures (private mode) */
    }
  },
  clear(): void {
    try {
      localStorage.removeItem(TOKEN_KEY)
    } catch {
      /* ignore */
    }
  },
}
