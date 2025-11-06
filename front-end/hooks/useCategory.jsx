import apiFetch from "@/utils/request"

const useCategories = () => {
  const fetchCategories = () => {
    const endpoint = `v1/elearning/categories`
    return apiFetch.post(`${endpoint}`)
  }

  return {
    fetchCategories
  }
}

export default useCategories
