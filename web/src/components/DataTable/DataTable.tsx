import type { ReactNode } from 'react'
import { ArrowDown, ArrowUp, ArrowUpDown, ChevronLeft, ChevronRight, Inbox } from 'lucide-react'
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
  emptyTitle?: string
  emptyAction?: ReactNode
  pagination?: PaginationState
}

/**
 * The workhorse data table (DESIGN-SYSTEM.md §5.4): sortable mono headers, row
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
  emptyTitle = 'Nothing here yet',
  emptyAction,
  pagination,
}: DataTableProps<T>) {
  const selectable = Boolean(onToggleRow)
  const colSpan = columns.length + (selectable ? 1 : 0)

  return (
    <div className={styles.container}>
      <div className={styles.scroll}>
        <table className={styles.table}>
          <caption className="sr-only">{caption}</caption>
          <thead>
            <tr>
              {selectable && <th className={cn(styles.th, styles.checkboxCell)} aria-label="Select" />}
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
                return (
                  <tr
                    key={id}
                    className={cn(styles.row, isSelected && styles.selected, rowIndex === rows.length - 1 && styles.lastRow)}
                  >
                    {selectable && (
                      <td className={cn(styles.td, styles.checkboxCell)}>
                        <Checkbox
                          label={`Select row ${rowIndex + 1}`}
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
