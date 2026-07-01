/**
 * Tiny classnames helper — joins truthy class values. Keeps component markup
 * readable without pulling in a dependency.
 */
export type ClassValue = string | false | null | undefined

export function cn(...classes: ClassValue[]): string {
  return classes.filter(Boolean).join(' ')
}
