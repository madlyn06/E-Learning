import { forwardRef, useId, useRef, useState } from 'react'
import { Button } from './Button'

/**
 * @typedef {"text" | "password" | "email" | "number" | "search" | "tel" | "url"} InputType
 */

/**
 * @typedef InputProps
 * @property {InputType} [type]
 * @property {string} [label]
 * @property {string} [className]
 * @property {string} [placeholder]
 * @property {string} [error]
 */

/**
 * @param {InputProps & import("react").InputHTMLAttributes<HTMLInputElement>} props
 */
export const Input = forwardRef(({ type = 'text', label, className = '', placeholder = '', error, ...rest }, ref) => {
  const id = useId()
  const inputRef = useRef(null)
  const [fileName, setFileName] = useState(null)

  const setRefs = (node) => {
    inputRef.current = node
    if (typeof ref === 'function') ref(node)
    else if (ref && typeof ref === 'object') ref.current = node
  }

  if (type === 'file') {
    const { onChange, disabled, accept, multiple, value: _ignoredValue, ...inputProps } = rest

    return (
      <fieldset className={`tf-field ${className}`}>
        <input
          placeholder={placeholder}
          type={type}
          className='tf-input style-1'
          id={id}
          style={{ display: 'none' }}
          aria-required='true'
          ref={setRefs}
          accept={accept}
          onChange={(e) => {
            const file = e.target.files?.[0]
            setFileName(file ? file.name : '')
            onChange && onChange(e)
          }}
          {...inputProps}
        />

        <Button
          type='button'
          icon=''
          className='px-4 py-1'
          variant='fourth'
          disabled={!!disabled}
          onClick={() => !disabled && inputRef.current?.click()}
        >
          {fileName ? `Đã chọn: ${fileName}` : 'Chọn file'}
        </Button>
        {error && <p className='form-error-message'>{error}</p>}
      </fieldset>
    )
  }

  return (
    <fieldset className={`tf-field ${className}`}>
      <input
        placeholder={placeholder}
        type={type}
        className='tf-input style-1'
        id={id}
        aria-required='true'
        ref={ref}
        {...rest}
      />

      {label && (
        <label className='tf-field-label fs-15' htmlFor={id}>
          {label}
        </label>
      )}
      {error && <p className='form-error-message'>{error}</p>}
    </fieldset>
  )
})

Input.displayName = 'Input'
