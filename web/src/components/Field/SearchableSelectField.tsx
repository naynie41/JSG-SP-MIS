import { forwardRef, useId, useMemo, useState } from 'react'
import { Search } from 'lucide-react'
import { SelectField } from './SelectField'
import type { SelectFieldProps, SelectOption } from './SelectField'
import { Icon } from '@/components/Icon/Icon'
import styles from './searchableSelect.module.css'

export interface SearchableSelectFieldProps extends SelectFieldProps {
  /** Only render the filter box once the list is long enough to need one. */
  searchThreshold?: number
  searchLabel?: string
  /**
   * The option to keep visible regardless of the filter.
   *
   * Separate from `value` on purpose: this component is used with react-hook-form's
   * uncontrolled `register()`, and setting `value` as well would make the select
   * controlled and fight it for ownership of the field.
   */
  pinnedValue?: string
}

/**
 * A native select with a filter box above it (DESIGN.md §5.2).
 *
 * Deliberately NOT a custom combobox. A hand-rolled listbox has to re-implement
 * keyboard navigation, typeahead, focus trapping, screen-reader announcements and
 * mobile behaviour — all of which the native control already does correctly, and all
 * of which are graded by NFR-USE-01 (WCAG 2.1 AA). Filtering the option list keeps
 * every one of those guarantees and still makes 22 MDAs findable by typing.
 *
 * The currently selected option is never filtered out: hiding it would make the
 * control appear to have silently lost the user's choice.
 */
export const SearchableSelectField = forwardRef<HTMLSelectElement, SearchableSelectFieldProps>(
  function SearchableSelectField(
    { options, searchThreshold = 8, searchLabel = 'Filter options', pinnedValue, ...rest },
    ref,
  ) {
    const [query, setQuery] = useState('')
    const searchId = useId()
    const statusId = useId()

    const showSearch = options.length >= searchThreshold

    const filtered = useMemo(() => {
      const term = query.trim().toLowerCase()

      if (term === '') {
        return options
      }

      return options.filter(
        (option: SelectOption) =>
          option.label.toLowerCase().includes(term) ||
          // Keep the current selection visible even when it does not match, so the
          // field never looks like it forgot what was chosen.
          (option.value !== '' && option.value === pinnedValue),
      )
    }, [options, query, pinnedValue])

    return (
      <div className={styles.wrap}>
        {showSearch && (
          <div className={styles.searchRow}>
            <label className={styles.searchLabel} htmlFor={searchId}>
              {searchLabel}
            </label>
            <div className={styles.searchBox}>
              <Icon icon={Search} size={16} className={styles.searchIcon} />
              <input
                id={searchId}
                type="search"
                className={styles.searchInput}
                value={query}
                placeholder="Type to narrow the list"
                aria-describedby={statusId}
                onChange={(event) => setQuery(event.target.value)}
              />
            </div>
            {/* Screen readers get the count; sighted users see the list shorten. */}
            <p id={statusId} className={styles.status} role="status">
              {query.trim() === ''
                ? `${options.length} options`
                : `${filtered.length} of ${options.length} match`}
            </p>
          </div>
        )}

        <SelectField ref={ref} options={filtered} {...rest} />
      </div>
    )
  },
)
