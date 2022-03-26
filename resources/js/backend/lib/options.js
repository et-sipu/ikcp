import axios from 'axios'

const get_branches = async app => {
  let { data } = await axios.get(app.route(`branches.search`), {
    params: {
      page: 1,
      perPage: 100,
      column: 'name'
    }
  })

  let branches = data.data.map(item => {
    return { id: item.id, name: item.name }
  })

  return branches
}

const get_departments = async app => {
  let { data } = await axios.get(app.route(`departments.search`), {
    params: {
      page: 1,
      perPage: 100,
      column: 'name'
    }
  })

  let departments = data.data.map(item => {
    return { id: item.id, name: item.name }
  })

  return departments
}

const get_doc_templates = async app => {
  let { data } = await axios.get(app.route(`doc_templates.search`), {
    params: {
      page: 1,
      perPage: 100,
      column: 'title',
      extraSearch: { template_types: ['ISM', 'Reports'] }
    }
  })

  let departments = data.data.map(item => {
    return { id: item.id, name: '(' + item.code + ') ' + item.title }
  })

  return departments
}

const get_employees = async app => {
  let { data } = await axios.get(app.route(`get_list_of_employees`))

  return data
}

export { get_branches, get_departments, get_employees, get_doc_templates }
