import axios from 'axios';

let base = '/frontend';

export const requestLogin = params => { return axios.post(`/login`, params).then(res => res.data); };

export const getUserListPage = params => { return axios.get(`${base}/users`, { params: params }); };

export const addUser = params => { return axios.post(`${base}/users`, params); };

export const removeUser = params => { return axios.delete(`${base}/users/${params.id}`, { params: params }); };

export const editUser = params => { return axios.put(`${base}/users/${params.id}`, params); };


export const getPersonListPage = params => { return axios.get(`${base}/person`, { params: params }); };

export const addPerson = params => { return axios.post(`${base}/person`, params); };

export const removePerson = params => { return axios.delete(`${base}/person/${params.id}`, { params: params }); };

export const editPerson = params => { return axios.put(`${base}/person/${params.id}`, params); };
