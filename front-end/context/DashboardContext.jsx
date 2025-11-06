import { createContext } from 'react'

const DashBoardContext = createContext({
  categories: undefined,
  courses: undefined,
  error: undefined,
  data: undefined,
  setError: () => {},
  setCategories: (categories) => {},
  setCourses: (courses) => {}
})

export default DashBoardContext
