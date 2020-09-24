const express = require('express')
const cors = require('cors')
const bodyParser = require('body-parser')
const { init } = require('./db')
const routes = require('./routes')

const app = express()
app.use(bodyParser.json())
app.use(cors())
app.use(routes)
app.use(express.static('client/build'))
app.set('view engine', 'pug')


init().then(() => {
  console.log('starting server on port 3001')
  app.listen(3001)
})
