#### Docker学习笔记
+ ##### 容器
   
	+ 学习 `Dockerfile` 文件

	Dockerfile 其本质就是一连串的 shell 命令脚本
	如下：
	```
	# Use an official Python runtime as a parent image //使用一个python官方的缓存作为源镜像
	FROM python:2.7-slim

	# Set the working directory to /app //设置一个工作目录
	WORKDIR /app

	# Copy the current directory contents into the container at /app //复制当前文件夹内容到容器中的工作目录
	COPY . /app

	# Install any needed packages specified in requirements.txt //安装写在 requirements.txt 中的包
	RUN pip install --trusted-host pypi.python.org -r requirements.txt

	# Make port 80 available to the world outside this container //使容器的80端口暴露出来
	EXPOSE 80

	# Define environment variable //定义环境变量
	ENV NAME World

	# Run app.py when the container launches //当容器初始化时执行命令
	CMD ["python", "app.py"]
	```

	`requirements.txt`
	```
	Flask
	Redis
	```

	`app.py`
	```
	from flask import Flask
	from redis import Redis, RedisError
	import os
	import socket

	# Connect to Redis
	redis = Redis(host="redis", db=0, socket_connect_timeout=2, socket_timeout=2)

	app = Flask(__name__)

	@app.route("/")
	def hello():
		try:
			visits = redis.incr("counter")
		except RedisError:
			visits = "<i>cannot connect to Redis, counter disabled</i>"

		html = "<h3>Hello {name}!</h3>" \
			   "<b>Hostname:</b> {hostname}<br/>" \
			   "<b>Visits:</b> {visits}"
		return html.format(name=os.getenv("NAME", "world"), hostname=socket.gethostname(), visits=visits)

	if __name__ == "__main__":
		app.run(host='0.0.0.0', port=80)
	```

	+ 根据Dockerfile建立容器

	`docker build --tag=friendlyhello .`
	将容器命名为 `friendlyhello`

	+ 运行容器
	
	`docker run -p 4000:80 friendlyhello`
	使用本机的4000端口监听容器的80端口

	`docker run -d -p 4000:80 friendlyhello`
	监听80端口并使用守护进程运行

	+ 停止容器
	
	`docker container stop <CONTAINER ID>`

	+ 上传容器镜像到个人空间
	
	`docker tag image username/repository:tag` //标记
	
	`docker push username/repository:tag` //推送

	+ 获取远程个人空间的镜像 
	
	`docker run -p 4000:80 username/repository:tag`

+ ##### 服务
   
   + 学习 `Docker-compose.yml` 语法
	yml文件有严格的格式要求，务必按照格式进行书写 https://docs.docker.com/compose/compose-file/
	```
	version: "3"
	#docker-compose.yml版本号，每个版本的变量及格式要求不同
	services:
	  web:
		# replace username/repo:tag with your name and image details
		image: username/repo:tag
		# deploy 必须在 swarm 中才可以被解析
		deploy:
		  replicas: 5
		  resources:
			limits:
			  cpus: "0.1"
			  memory: 50M
		  restart_policy:
			condition: on-failure
		# 暴露端口
		ports:
		  - "4000:80"
		# 网络标识
		networks:
		  - webnet
	networks:
	  webnet:
	```
	
   + 在负载均衡的容器中运行app
   
    `docker swarm init` 初始化swarm
    
	`docker stack deploy -c docker-compose.yml getstartedlab` 根据 yml 文件配置 getstartedlab 服务
	
	`docker service ls` 查看已启动的服务
	```
	docker stack services getstartedlab
	ID                  NAME                MODE                REPLICAS            IMAGE               PORTS
	bqpve1djnk0x        getstartedlab_web   replicated          5/5                 username/repo:tag   *:4000->80/tcp
	```
	服务中每个容器被称作 task 
	
	`docker service ps getstartedlab_web` 查看服务中的 task 
	
	也可以使用 
	
	`docker container ls -q` 查看系统中所有的容器
	
	```
	docker stack ps getstartedlab
	ID                  NAME                  IMAGE               NODE                DESIRED STATE       CURRENT STATE           ERROR               PORTS
	uwiaw67sc0eh        getstartedlab_web.1   username/repo:tag   docker-desktop      Running             Running 9 minutes ago                       
	sk50xbhmcae7        getstartedlab_web.2   username/repo:tag   docker-desktop      Running             Running 9 minutes ago                       
	c4uuw5i6h02j        getstartedlab_web.3   username/repo:tag   docker-desktop      Running             Running 9 minutes ago                       
	0dyb70ixu25s        getstartedlab_web.4   username/repo:tag   docker-desktop      Running             Running 9 minutes ago                       
	aocrb88ap8b0        getstartedlab_web.5   username/repo:tag   docker-desktop      Running             Running 9 minutes ago
	```
	
	+ 重新规划负载均衡的规模
	修改 deploy 的 replicas 值，并重新生成服务
	`docker stack deploy -c docker-compose.yml getstartedlab`
	
	+ 卸载服务
	`docker stack rm getstartedlab`
	+ 退出swarm
	`docker swarm leave --force`