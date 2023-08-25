/*
 * @Author: lkw199711 lkw199711@163.com
 * @Date: 2023-05-03 11:35:53
 * @LastEditors: lkw199711 lkw199711@163.com
 * @LastEditTime: 2023-05-21 13:34:00
 * @FilePath: \smanga\src\api\init.ts
 */
import {ajax} from './index';

export function system_init(userName: string, passWord: string) {
	return ajax({
		timeout: 3 * 60 * 1000,
		url: 'deploy/database-init',
		data: {userName, passWord},
	});
}
