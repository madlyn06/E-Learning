module.exports = {
  apps: [
    {
      name: 'elearning-app',
      script: 'node_modules/next/dist/bin/next',
      args: 'start -p 3000', // chạy ở port 3000
      env: {
        NODE_ENV: 'production'
      }
    }
  ]
};