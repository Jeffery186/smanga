/*
 * @Author: error: error: git config user.name & please set dead value or install git && error: git config user.email & please set dead value or install git & please set dead value or install git
 * @Date: 2023-08-16 03:30:05
 * @LastEditors: error: error: git config user.name & please set dead value or install git && error: git config user.email & please set dead value or install git & please set dead value or install git
 * @LastEditTime: 2023-08-16 03:34:49
 * @FilePath: /smanga/src/api/image.ts
 */
import Axios from 'axios';
import {url} from '@/api';
import {Cookies} from '@/utils';
import Qs from 'qs';
/**
 * 文件 图片请求
 * @type {Axios}
 */
const img = Axios.create({
	baseURL: url + 'image/get',
	timeout: 15 * 1000,
	method: 'post',
	responseType: 'blob', // 设置接收格式为blob格式
	params: {},
	headers: {
		'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
	},
	transformRequest: [
		(data) => {
			// 用户标识
			const userId = Cookies.get('userId');
			// 获取时间戳
			const timestamp = new Date().getTime();
			// 初始化传参
			data = data || {};
			// 加入时间戳与密钥
			data = Object.assign(data, {
				userId,
				timestamp,
			});
			// 返回json
			return Qs.stringify(data);
		},
	],
	transformResponse: [
		function (data) {
			data = data || {};

			return URL.createObjectURL(data);
		},
	],
});

const imageApi = {
	/**
	 * @description: 获取图片文件 blob
	 * @param {string} file
	 * @return {*}
	 */
	async get(file: string) {
		const res = img({data: {file}});

		return (await res).data;
	},
};

export default imageApi;
