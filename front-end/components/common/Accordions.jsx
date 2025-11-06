import React, { useId, useRef, useLayoutEffect } from 'react'

export default function Accordions({ childrenHeader, childrenContent }) {
  const id = useId()
  const contentRef = useRef(null)
  const [isOpen, setIsOpen] = React.useState(true)
  const [height, setHeight] = React.useState('auto')

  useLayoutEffect(() => {
    if (contentRef.current) {
      if (isOpen) {
        setHeight(contentRef.current.scrollHeight + 'px')
      } else {
        setHeight('0px')
      }
    }
  }, [isOpen, childrenContent])

  const toggleAccordion = (e) => {
    // Check if the click target is an interactive element that shouldn't trigger collapse
    const target = e.target
    const isInteractive =
      target.closest('button') ||
      target.closest('input') ||
      target.closest('select') ||
      target.closest('textarea') ||
      target.closest('[data-no-collapse]') ||
      target.closest('svg') || // Icon elements
      target.closest('path') || // SVG path elements
      target.tagName === 'svg' ||
      target.tagName === 'path'

    if (isInteractive) {
      e.preventDefault()
      e.stopPropagation()
      return
    }

    setIsOpen(!isOpen)
  }

  return (
    <>
      <div className='tf-accordion-item'>
        <h3 className='tf-accordion-header'>
          <span
            className={`tf-accordion-button ${isOpen ? '' : 'collapsed'}`}
            type='button'
            onClick={toggleAccordion}
            aria-expanded={isOpen}
            aria-controls={`collapse${id}`}
          >
            <span className='rectangle-314' />
            {childrenHeader}
          </span>
        </h3>
        <div
          id={`collapse${id}`}
          className='tf-accordion-collapse'
          style={{
            height: height,
            overflow: 'hidden',
            transition: 'height 0.3s ease-in-out'
          }}
          ref={contentRef}
        >
          <div className='tf-accordion-content'>{childrenContent}</div>
        </div>
      </div>
    </>
  )
}
