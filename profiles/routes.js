const express = require('express')
const pug = require('pug');
const { insertUnit, getUnits, updateUnit } = require('./db')
const unitSchema = require('./unitSchema');

const router = express.Router()

router.get('/', (req, res) => {
  return res.sendFile(__dirname + '/client/build/index.html')
});

router.post('/unit', (req, res) => {
  const unit = req.body
  const result = unitSchema.validate(unit)
  if (result.error) {
    console.error(result.error)
    res.status(400).end()
    return
  }
  insertUnit(unit)
    .then(() => {
      res.status(200).end()
    })
    .catch((err) => {
      console.error(err)
      res.status(500).end()
    })
})

router.get('/units', (req, res) => {
  getUnits()
    .then((units) => {
      units = units.map((unit) => {
        const { _id, ...restProps } = unit;
        return {
          id: _id,
          ...restProps
        }
      })
      res.json(units)
    })
    .catch((err) => {
      console.error(err)
      res.status(500).end()
    })
})

router.put('/unit/:id', (req, res) => {
  const { id } = req.params
  const unit = req.body;
  unit.updatedAt = new Date();
  updateUnit(id, unit)
    .then(() => {
      res.status(200).end()
    })
    .catch((err) => {
      console.error(err)
      res.status(500).end()
    })
})

module.exports = router
