import { useEffect, useRef } from 'react'
import { createPortal } from 'react-dom'

export default function Modal({ isOpen, onClose, title, children }) {
  const modalRef = useRef(null)

  useEffect(() => {
    function handleClickOutside(event) {
      const fullscreenLoading = document.querySelector('.fullscreen-loading')
      const isLoading = fullscreenLoading && fullscreenLoading.style.display !== 'none'
      if (modalRef.current && !modalRef.current.contains(event.target) && isOpen && !isLoading) {
        onClose()
      }
    }

    document.addEventListener('mousedown', handleClickOutside)
    return () => {
      document.removeEventListener('mousedown', handleClickOutside)
    }
  }, [isOpen, onClose])

  if (!isOpen) return null

  if (typeof window === 'undefined') return null

  return createPortal(
    <div className='aaf-overlay'>
      <div
        className='aaf-modal'
        ref={modalRef}
        data-no-collapse
        onMouseDown={(e) => e.stopPropagation()}
        onClick={(e) => e.stopPropagation()}
      >
        <div className='aaf-header'>
          <h2 className='aaf-title fs-3'>{title}</h2>
          <button className='aaf-closeButton' onClick={onClose} type='button'>
            <svg width='24' height='24' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
              <path strokeLinecap='round' strokeLinejoin='round' strokeWidth={2} d='M6 18L18 6M6 6l12 12' />
            </svg>
          </button>
        </div>
        <div className='mx-5 my-4'>{children}</div>
      </div>
    </div>,
    document.body
  )
}
