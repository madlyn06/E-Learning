import Processing, { useProcessing } from '@/components/ui/Processing'
import { createContext } from 'react'

export const FeedbackContext = createContext({
  toggleProcessing: () => {}
})

const FeedbackContextProvider = ({ children }) => {
  const [processing, toggleProcessing] = useProcessing(false)

  return (
    <>
      <FeedbackContext.Provider value={{ toggleProcessing }}>{children}</FeedbackContext.Provider>
      <Processing visible={processing} />
    </>
)
}

export default FeedbackContextProvider
