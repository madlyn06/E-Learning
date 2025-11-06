import { forwardRef, useId } from 'react'

// eslint-disable-next-line react/display-name
export const Textarea = forwardRef(({ label, placeholder = '', error, rows = 4, defaultValue, ...rest }, ref) => {
  const id = useId()

  return (
    <fieldset className='tf-field'>
      <textarea
        ref={ref}
        id={id}
        className='tf-input style-1'
        placeholder={placeholder}
        rows={rows}
        aria-required='true'
        {...rest}
      />

      {label && (
        <label className='tf-field-label type-textarea fs-15' htmlFor={id}>
          {label}
        </label>
      )}

      {error && <p className='form-error-message'>{error}</p>}
    </fieldset>
  )
})
