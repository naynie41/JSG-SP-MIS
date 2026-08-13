import { Fragment } from 'react'
import type { ReactNode } from 'react'
import { ArrowDown, ArrowUp, ArrowUpDown, ChevronDown, ChevronLeft, ChevronRight, Inbox } from 'lucide-react'
import { cn } from '@/lib/utils/cn'
import { Icon } from '@/components/Icon/Icon'
import { Button } from '@/components/Button/Button'
import { Checkbox } from '@/components/Field/Checkbox'
import styles from './DataTable.module.css'

export type SortDirection = 'asc' | 'desc'

export interface Column<T> {
  key: string
  header: string
  render: (row: T) => ReactNode
  align?: 'left' | 'right'
  sortable?: boolean
}

export interface SortState {
  key: string
  direction: SortDirection
}

export interface PaginationState {
  page: number
  pageCount: number
  onPageChange: (page: number) => void
}

export interface DataTableProps<T> {
  columns: Column<T>[]
  rows: T[]
  getRowId: (row: T) => string
  caption: string
  loading?: boolean
  skeletonRows?: number
  sort?: SortState
  onSortChange?: (key: string) => void
  selectedIds?: ReadonlySet<string>
  onToggleRow?: (id: string) => void
  /**
   * Select/clear every row currently rendered. Receives the ids being governed
   * so the caller does not have to re-derive them, and `nextSelected` so it can
   * set state directly rather than diffing. Without this the header checkbox is
   * not rendered — a table whose selection is externally driven keeps the old
   * blank header.
   */
  onToggleAll?: (ids: string[], nextSelected: boolean) => void
  /**
   * Accessible label for a row's select/expand controls. Defaults to the row
   * ordinal, which is meaningless in a long batch ("Select row 147"); pass
   * something the user can recognise, e.g. the person's name.
   */
  getRowLabel?: (row: T) => string
  /** Render an expansion panel below a row (adds a leading disclosure column). */
  renderExpanded?: (row: T) => ReactNode
  expandedIds?: ReadonlySet<string>
  onToggleExpand?: (id: string) => void
  emptyTitle?: string
  emptyAction?: ReactNode
  pagination?: PaginationState
}

/**
 * The workhorse data table (DESIGN.md §5.4): sortable mono headers, row
 * hover/selected states, optional selection column, skeleton loading, a real
 * empty state, and pagination.
 */
export function DataTable<T>({
  columns,
  rows,
  getRowId,
  caption,
  loading = false,
  skeletonRows = 5,
  sort,
  onSortChange,
  selectedIds,
  onToggleRow,
  onToggleAll,
  getRowLabel,
  renderExpanded,
  expandedIds,
  onToggleExpand,
  emptyTitle = 'Nothing here yet',
  emptyAction,
  pagination,
}: DataTableProps<T>) {
  const selectable = Boolean(onToggleRow)
  const expandable = Boolean(renderExpanded)
  const colSpan = columns.length + (selectable ? 1 : 0) + (expandable ? 1 : 0)

  // Select-all governs the rows currently rendered, not the whole result set —
  // on a paginated table "all" can only honestly mean "all of what you can see".
  const pageIds = rows.map(getRowId)
  const selectedOnPage = pageIds.filter((id) => selectedIds?.has(id)).length
  const allSelected = pageIds.length > 0 && selectedOnPage === pageIds.length
  const someSelected = selectedOnPage > 0 && !allSelected

  return (
    <div className={styles.container}>
      <div className={styles.scroll}>
        <table className={styles.table}>
          <caption className="sr-only">{caption}</caption>
          <thead>
            <tr>
              {expandable && <th className={cn(styles.th, styles.checkboxCell)} aria-label="Expand" />}
              {selectable && (
                <th className={cn(styles.th, styles.checkboxCell)}>
                  {onToggleAll ? (
                    <Checkbox
                      label={allSelected ? 'Clear selection' : `Select all ${pageIds.length} rows`}
                      hideLabel
                      checked={allSelected}
                      indeterminate={someSelected}
                      disabled={pageIds.length === 0}
                      onChange={() => onToggleAll(pageIds, !allSelected)}
                    />
                  ) : (
                    <span className="sr-only">Select</span>
                  )}
                </th>
              )}
              {columns.map((col) => {
                const isSorted = sort?.key === col.key
                return (
                  <th
                    key={col.key}
                    className={cn(styles.th, col.align === 'right' && styles.right)}
                    aria-sort={isSorted ? (sort!.direction === 'asc' ? 'ascending' : 'descending') : undefined}
                  >
                    {col.sortable && onSortChange ? (
                      <button type="button" className={styles.sortButton} onClick={() => onSortChange(col.key)}>
                        {col.header}
                        <span className={cn(styles.sortIcon, isSorted && styles.sortActive)}>
                          <Icon
                            icon={isSorted ? (sort!.direction === 'asc' ? ArrowUp : ArrowDown) : ArrowUpDown}
                            size={14}
                          />
                        </span>
                      </button>
                    ) : (
                      col.header
                    )}
                  </th>
                )
              })}
            </tr>
          </thead>
          <tbody>
            {loading &&
              Array.from({ length: skeletonRows }).map((_, rowIndex) => (
                <tr key={`skeleton-${rowIndex}`}>
                  {expandable && <td className={styles.td} />}
                  {selectable && <td className={styles.td} />}
                  {columns.map((col) => (
                    <td key={col.key} className={styles.td}>
                      <span className={styles.skeleton} style={{ width: `${40 + ((rowIndex + col.key.length) % 5) * 10}%` }} />
                    </td>
                  ))}
                </tr>
              ))}

            {!loading && rows.length === 0 && (
              <tr>
                <td colSpan={colSpan}>
                  <div className={styles.empty}>
                    <Icon icon={Inbox} size={28} />
                    <span className={styles.emptyTitle}>{emptyTitle}</span>
                    {emptyAction}
                  </div>
                </td>
              </tr>
            )}

            {!loading &&
              rows.map((row, rowIndex) => {
                const id = getRowId(row)
                const isSelected = selectedIds?.has(id) ?? false
                const isExpanded = expandedIds?.has(id) ?? false
                const isLast = rowIndex === rows.length - 1
                const rowLabel = getRowLabel?.(row) ?? `row ${rowIndex + 1}`
                return (
                  <Fragment key={id}>
                    <tr className={cn(styles.row, isSelected && styles.selected, isLast && !isExpanded && styles.lastRow)}>
                      {expandable && (
                        <td className={cn(styles.td, styles.checkboxCell)}>
                          <button
                            type="button"
                            className={styles.disclosure}
                            aria-expanded={isExpanded}
                            aria-label={isExpanded ? `Collapse ${rowLabel}` : `Expand ${rowLabel}`}
                            onClick={() => onToggleExpand?.(id)}
                          >
                            <Icon icon={ChevronDown} size={16} className={cn(styles.chevron, isExpanded && styles.chevronOpen)} />
                          </button>
                        </td>
                      )}
                      {selectable && (
                        <td className={cn(styles.td, styles.checkboxCell)}>
                          <Checkbox
                            label={`Select ${rowLabel}`}
                            hideLabel
                            checked={isSelected}
                            onChange={() => onToggleRow?.(id)}
                          />
                        </td>
                      )}
                      {columns.map((col) => (
                        <td key={col.key} className={cn(styles.td, col.align === 'right' && styles.right)}>
                          {col.render(row)}
                        </td>
                      ))}
                    </tr>
                    {expandable && isExpanded && (
                      <tr className={styles.expandedRow}>
                        <td className={cn(styles.td, isLast && styles.lastRow)} colSpan={colSpan}>
                          {renderExpanded!(row)}
                        </td>
                      </tr>
                    )}
                  </Fragment>
                )
              })}
          </tbody>
        </table>
      </div>

      {pagination && pagination.pageCount > 1 && (
        <div className={styles.footer}>
          <span className={styles.pageInfo}>
            Page {pagination.page} of {pagination.pageCount}
          </span>
          <div className={styles.pageControls}>
            <Button
              size="sm"
              variant="tertiary"
              leftIcon={ChevronLeft}
              disabled={pagination.page <= 1}
              onClick={() => pagination.onPageChange(pagination.page - 1)}
            >
              Prev
            </Button>
            <Button
              size="sm"
              variant="tertiary"
              rightIcon={ChevronRight}
              disabled={pagination.page >= pagination.pageCount}
              onClick={() => pagination.onPageChange(pagination.page + 1)}
            >
              Next
            </Button>
          </div>
        </div>
      )}
    </div>
  )
}
