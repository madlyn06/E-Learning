import { useReducer } from 'react'
import { createPortal } from 'react-dom'

export const useProcessing = (initialState = false) => {
  const [processing, toggleProcessing] = useReducer((processing) => !processing, initialState)

  return [processing, toggleProcessing]
}

const Processing = ({ visible }) => {
  if (typeof window === 'undefined') {
    return null
  } else {
    return createPortal(
      <>
        {visible && (
          <div className='fullscreen-loading'>
            <div className='loading-container'>
              <div className='spinner-wrapper'>
                <div className='spinner-outer'></div>
                <div className='spinner-inner'></div>
              </div>

              <div className='loading-dots'>
                <div className='dot dot-1'></div>
                <div className='dot dot-2'></div>
                <div className='dot dot-3'></div>
              </div>
            </div>
          </div>
        )}
      </>,
      document.body
    )
  }
}

export default Processing
