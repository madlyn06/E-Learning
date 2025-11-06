import apiFetch from '@/utils/request'
import useSWR from 'swr'
import { fetcher } from '@/utils/fetcher'
import { useCallback, useState } from 'react'

export function useCourseInstructor() {
  const [page, _setPage] = useState(1)

  const { data, error, isLoading, mutate } = useSWR(`/v1/elearning/instructor-courses?page=${page}`, fetcher)

  const setPage = useCallback((page) => {
    _setPage(page)
  }, [])

  const createCourse = async (data) => {
    await apiFetch.post('/v1/elearning/courses', data)
    await mutate()
  }

  return [
    { data, error, isLoading, mutate, page },
    { createCourse, setPage }
  ]
}
