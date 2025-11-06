export const LS_KEYS = {
  PROFILE: 'profile',
  TOKEN: 'token',
  SETTINGS: 'settings'
}

export const LESSON_TYPE = {
  FILE: 'file',
  VIDEO: 'video',
  QUIZ: 'quiz',
  YOUTUBE: 'youtube'
}

export const LESSON_TYPE_OPTIONS = [
  {
    value: LESSON_TYPE.YOUTUBE,
    label: 'Youtube'
  },
  {
    value: LESSON_TYPE.VIDEO,
    label: 'Video'
  },
  {
    value: LESSON_TYPE.FILE,
    label: 'File'
  },
  {
    value: LESSON_TYPE.QUIZ,
    label: 'Quiz'
  },
]

export const LESSON_TYPE_ICON = {
  [LESSON_TYPE.FILE]: 'flaticon-document',
  [LESSON_TYPE.VIDEO]: 'flaticon-play-1',
  [LESSON_TYPE.QUIZ]: 'flaticon-question'
}
