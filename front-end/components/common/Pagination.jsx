'use client'

import React, { useEffect, useState } from 'react'

export default function Pagination({
  currentPage = 1,
  total = 200,
  perPage = 10,
  setPage = (num) => {},
  maxVisiblePages = 5
}) {
  const [activePage, setActivePage] = useState(currentPage)
  const totalPages = Math.ceil(total / perPage)

  useEffect(() => {
    setActivePage(currentPage)
  }, [currentPage])

  const handlePageClick = (page) => {
    if (page >= 1 && page <= totalPages && page !== activePage) {
      setActivePage(page)
      setPage(page)
    }
  }

  const getVisiblePages = () => {
    if (totalPages <= maxVisiblePages) {
      return Array.from({ length: totalPages }, (_, i) => i + 1)
    }

    const sidePages = Math.floor((maxVisiblePages - 3) / 2)
    let startPage = Math.max(activePage - sidePages, 1)
    let endPage = Math.min(activePage + sidePages, totalPages)

    if (activePage <= sidePages + 1) {
      endPage = Math.min(maxVisiblePages - 2, totalPages - 1)
    } else if (activePage >= totalPages - sidePages) {
      startPage = Math.max(totalPages - maxVisiblePages + 3, 2)
    }

    const pages = []

    pages.push(1)

    if (startPage > 2) {
      pages.push('...')
    }

    for (let i = startPage; i <= endPage; i++) {
      if (i !== 1 && i !== totalPages) {
        pages.push(i)
      }
    }

    if (endPage < totalPages - 1) {
      pages.push('...')
    }

    if (totalPages > 1) {
      pages.push(totalPages)
    }

    return pages
  }

  const visiblePages = getVisiblePages()

  if (totalPages <= 1) {
    return null
  }
  return (
    <>
      {totalPages > 1 ? (
        <React.Fragment>
          <li onClick={() => handlePageClick(activePage - 1)}>
            <a>
              <i className='icon-arrow-left' />
            </a>
          </li>
          {visiblePages.map((page, index) => {
            if (page === '...') {
              return (
                <li className='' key={`ellipsis-${index}`}>
                  <a className=''>...</a>
                </li>
              )
            }

            return (
              <li className={` ${activePage === page ? 'active' : ''}`} key={page}>
                <a className='' onClick={() => handlePageClick(page)}>
                  {page}
                </a>
              </li>
            )
          })}
          <li onClick={() => handlePageClick(activePage + 1)}>
            <a>
              <i className='icon-arrow-right' />
            </a>
          </li>
        </React.Fragment>
      ) : (
        ''
      )}
    </>
  )
}
