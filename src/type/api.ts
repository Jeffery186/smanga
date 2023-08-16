/*
 * @Author: lkw199711 lkw199711@163.com
 * @Date: 2023-07-27 23:14:22
 * @LastEditors: lkw199711 lkw199711@163.com
 * @LastEditTime: 2023-07-28 15:56:43
 * @FilePath: \smanga\src\type\api.ts
 */
interface ResType {
	code: number;
	data: any;
	list: {
		data: [];
		total: number;
	} & [];
	message: string;
	status: string;
	eMsg: string;
	request: string;
}
